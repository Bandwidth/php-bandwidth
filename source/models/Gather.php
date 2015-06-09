<?php

/**
 * @model Gather
 * http://ap.bandwidth.com/docs/rest-api/calls/#resource413
 *
 *
 * Provides Gathers, through calls
 */
namespace Catapult;


class Gather extends GenericResource {
   /**
    * CTor for gather resource. 
    * Init Forms
    *
    * GET
    * Gather('call-id')
    * Gather('call-id', 'gather-id')
    *
    * POST
    * Gather('call-id', array)
    * Gather(array) 
    */
    public function __construct()
    {
      $data = Ensure::Input(func_get_args());
      parent::_init($data, new DependsResource(
        array(
          array("term" => "calls", "plural" => true)
        )),
        new LoadsResource(
          array("parent" => false, "primary" => "create", "id" => "id", "init" => array("callId"), "silent" => true)
        ),
        new SchemaResource(
          array("fields" => array(
              "maxDigits", "interDigitTimeout", "terinatingDigits", "tag", "prompt.sentance", 
              "prompt.gender", "prompt.fileUrl", "prompt.loopEnabled", "prompt.bargeable"
          ), "needs" => array("id"))
         )
      );
    }

    /**
     * Update the gather DTMF
     * The only update allowed is state:completed
     * to stop the gather
     *
     * @return Gather 
     */
    public function stop()
    {
      $url = URIResource::Make($this->path, array($this->call_id, "gather", $this->id));
      $data = new DataPacket(array("state" => GATHER_STATES::completed));
      $this->client->post($url, $data->get());

      return Constructor::Make($this, $data->get());
    }
}
