<?php
/**
 *
 * @model Bridge
 * http://ap.bandwidth.com/docs/rest-api/bridges/
 *
 *
 * Joins calls in one bridge
 * functions:
 * create/1
 * fetchCalls/1
 * callIds/1
 * callParty/polymorphic
 *
 */
namespace Catapult;

final class Bridge Extends GenericResource {
    /**
     * Bridge call CTor accept calls 
     * as main argument
     *
     * Init forms
     * GET
     * Bridge('bridge-id')
     * Bridge()
     *
     * POST
     * Bridge(array) 
     */
    public function __construct($data=null)
    {
      $data = Ensure::Input($data);

      parent::_init($data, new DependsResource,
        new LoadsResource(array(
          "primary" => "get", "id" => "id", "init" => "", "silent" => false,
        )),
        new SchemaResource(array(
          "fields" => array('audio', 'completedTime', 'createdTime', 'activatedTime', 'callIds'),
          "needs" => array("id")
          )
        ),
        new SubFunctionResource(array( 
          array("type" => "get", "term" => "calls")
        ))
      );
    }

    
    /**
     * Add another to a call bridge
     *
     * @param caller -> PhoneNumber
     * @param callee -> PhoneNumber
     * @param args -> Call's arguments (see in function)
     */
    public function callParty($caller, $callee, $args)
    {
      $new_call = Call::create($caller, $callee, $this->id, $args);
      $this->calls ++; 

      return Constructor::Make($this, $data->get());
    }

    /** 
     * Return all the call ids
     * for a given bridge
     */
    public function callIds()
    {
      $call_ids = array();	

      foreach ($this->calls as $call)
        $call_ids[] = $call->id;

      return $call_ids;
    }

    /**
     * Fetch all the calls
     * from a bridge
     * 
     * @return: list of calls
     *
     */
    public function fetchCalls()
    {
      $url = URIResource::Make($this->path, array($this->id, "calls"));
      $res = $this->client->get($url);	
      $this->calls = new CallCollection(new DataPacketCollection($res));

      return $this->calls;
    }

    /**
     * Get the audio url for a bridge
     *
     * @return: fully qualified url
     */
    public function getAudioUrl()
    {
      return URIResource::Make($this->path, array($this->id, "audio"));
    }
}
