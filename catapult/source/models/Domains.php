<?php
/**
 * @model Domains
 * http://ap.bandwidth.com/docs/rest-api/domains-2/ 
 * 
 * Provides SIP Domains functions 
 */

namespace Catapult;

final class Domains extends GenericResource {

  /**
   * construct the domains as initiated
   * or new. Domains should have functions
   * to access their endpoints as well
   *
   * init forms
   * GET:
   * Domains('domain-id')
   * Domains()
   *
   * POST
   * Domains(array)
   */
  public function __construct() {
    $data = Ensure::Input(func_get_args());

    return parent::_init($data, new DependsResource, 
      new LoadsResource( 
        array("primary" => "GET", "id" => "id", "init" => TRUE, "silent" => FALSE)
     ), 
     new SchemaResource(
        array("fields" => array('name', 'description'), "needs" => array('id', 'name')
        )
     ), 
     new SubFunctionResource
    );
  }

  /**
   * List all the endpoints
   * for a given Domain.
   *
   * @returns EndpointsCollection
   */
  public function listEndpoints() {
    $uri = new URIResource($this->path, array($this->id, "endpoints"));

    return new EndpointsCollection(new DataPacketCollection($this->client->get((string) $uri)));
  }
}
