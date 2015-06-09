<?php
/**
 * @model NumberInfo
 * http://ap.bandwidth.com/docs/rest-api/numberinfo/
 *
 * Provides access to CNAM phone number information.
 */
namespace Catapult;
final class NumberInfo extends GenericResource {
    /**
     * CTor for NumberInfo
     * Init Forms:
     *
     * GET
     * NumberInfo('cname-number') 
     *
     */
    public function __construct() {
      $data = Ensure::Input(func_get_args());
      parent::_init($data, new DependsResource(array(
        array(
          "term" => "phoneNumbers",
          "plural" => true
        ))),
      new LoadsResource(array("primary" => "GET", "init" => array(), "id" => "number", "silent" => false)),
      new SchemaResource(array("needs" => array('name', 'number', 'created', 'updated'), "fields" => array('name', 'number')))
      );
    }

    /**
     * Provide number information
     * 
     * @param number: CNAM number
     */
    public function get($number=null)
    {
      $url = URIResource::Make($this->path, array($number));
      $data = $this->client->get($url, array(), true, false);

      return Constructor::Make($this, $data);
    }
}
