<?php
/**
 * @Application 
 * http://ap.bandwidth.com/docs/rest-api/applications/
 *
 * provides:
 * patch/1
 * get/1
 * create/polymorphic
 */

namespace Catapult;

final class Application extends GenericResource {
    /**
     * CTor for application
     *
     * Init forms:
     * GET
     * Application('application-id')
     *
     * POST
     * Application(array)
     * Application()
     */
    public function __construct() {
      $data = Ensure::Input(func_get_args());
        parent::_init($data, new DependsResource,
        new LoadsResource(
          array("primary" => "GET", "id" => "id", "silent" => false, "init" => array())
        ),
        new SchemaResource(array(
          "fields" => array(
          'id', 'name', 'incomingCallUrl', 'incomingCallUrlCallbackTimeout',
          'incomingCallFallbackUrl', 'incomingSmsUrl', 'incomingSmsUrlCallbackTimeout',
          'incomingSmsFallbackUrl', 'callbackHttpMethod', 'autoAnswer'
        ),
       "needs" => array("id", "name")))
      );
    }

    /**
    * Patch the given
    * application with new information
    * same as update/1
    *
    * @param data: set of application data
    */
    public function patch($data)
    {
      $data = Ensure::Input($data);
      $this->client->post($this->path . "/" . $this->id, $data->get());
      // reload after recreating
      return Constructor::make($this, array("id" => $this->id), TRUE);
    }
} 
