<?php
/**
 * @object EndpointsMulti
 *
 * batch like interface for creating
 * endpoints.
 */

namespace Catapult;
final class EndpointsMulti extends CollectionObject {
  /**
   * needs a domainId
   * for init this will
   * be used for all the endpoints
   */
  public function __construct($domain) {
    if ($domain instanceof Domains) {
      $this->domain = $domain->id;
    } elseif (is_string($domain)) { 
      $this->domain = $domain;
    }
    $this->done = false;
    $this->queued = 0;
  }
  /**
   * Implementors Note:
   *
   * Multi objects should use a Collection likee
   * interface this will eventually become
   * MultiObject
   *
   * we need to provide getName/1 for collection
   * reasons
   */
  public function getName() {
    return "Endpoints";
  }
  /**
   * push an endpoint
   * by the assoc array or params object
   * 
   * @param data: Parameters or array
   */
  public function pushEndpoint(/** polymorphic **/) {
    $data = Ensure::Input(func_get_args());

    $this->data[] = $data->get();
    $this->queued ++;
  }
  public function execute() {
    if ($this->done) {
      throw new CatapultApiException("You've already done this.");
    }
    $created = array();
    foreach ($this->data as $k => $d) {
      $endpoint = new Endpoints;
      $d['domainId'] = $this->domain;
      $created[$k] = $endpoint->create($d);

      $this->queued --;
    }
    $this->done = true;
    return $created;
  }
}
