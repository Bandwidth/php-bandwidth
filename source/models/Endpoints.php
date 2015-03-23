<?php
/**
 * @model Endpoints
 *
 * http://ap.bandwidth.com/docs/rest-api/endpoints-2/
 *
 * Provides endpoints functionality
 */ 

namespace Catapult;
  
final class Endpoints extends GenericResource {

  /**
   * construct the endpoint as initiated
   * or a new one. endpoints take domains
   * as a dependancy so we need one on init
   *
   * init forms
   *
   * GET:
   * Endpoints('domain-id', 'endpoints-id')
   * Endpoints()
   *
   * POST: 
   * Endpoints('domain-id', array)
   * Endpoints(array)
   */
  public function __construct() {
    $data = Ensure::Input(func_get_args());
    return parent::_init($data, new DependsResource(array(
         array("term" => "domains", "plural" => TRUE)
      )), 
      new LoadsResource(
         array("parent" => false, "primary" => "create", "init" => array("domainId"), "id" => "id", "silent"=> TRUE)
      ), 
      new SchemaResource(array(
        "fields" => array('id', 'name', 'description', 'applicationId', 'domainId', 'sipUri', 'enabled', 'credentials'), 
        "needs" => array('name', 'domainId', 'credentials')
      ))
     );
  }

  /**
   * Endpoints overloads the load/1
   * function we need to rebuild its
   * path resource
   * we need: 
   * domains/{domain-id}/endpoints/{endpoint-id}
   *
   * Implementors note:
   * this was added to get around CollectionObject's
   * listAll function which will need to
   * set up this without having to do another RESTful get
   */
  public function load() {
    $data = Ensure::Input(func_get_args());
    $data = $data->get();

    return parent::load($data,  
      new PathResource($this,array(
        "domains" => $data['domainId'], 
        "endpoints" => ""
      )
    ));
  }
 
  /**
   * Create musn't preserve the domainId
   * as this will be our path. We will pass
   * it in the parameter, use PathResource
   * then remove it
   *
   * @param: args Endpoint create data
   */ 
  public function create() {
    $data = Ensure::Input(func_get_args());
    $data = $data->get();
    if (!isset($data['domainId'])) {
      $data['domainId'] = '';
    }

    return parent::create($data, 
      new RemoveResource($this, array("domainId"),
      new PathResource($this, array( 
         "domains" => $data['domainId'],
         "endpoints" => ""       
          )
      ))
    );
  }

  /** 
   * get the credentials
   * as a EndpointsCredentials
   * object more on this in types
   *
   * @returns EndpointsCredentials
   */
  public function getCredentials() {
    return new EndpointsCredentials($this->credentials);
  }
}
