<?php

/**
 * @model CallEvents
 * http://ap.bandwidth.com/docs/rest-api/calls/#resource408
 *
 * Provides call event information.
 */
namespace Catapult;
/**
 * Note:
 * Important keep name consistent
 * with API. These can be easily 
 * confused for 'Event'. There is 
 * a property in genericResource that
 * will switch to this whenever looking
 * for a subclass. i.e being called directly
 * from class Call
 */

final class CallEvents extends GenericResource {
  /**
   * 
   * CallEvents do not directly provide 
   * GET or POST functions they can be accessed
   * by calls only.
   *
   * Init Forms:
   *
   * GET:
   * CallEvents()
   * CallEvents('event-id')
   */
  public function __construct() {
    $data = Ensure::Input(func_get_args());
    parent::_init($data, new DependsResource(array(
       array(
       "term" => "calls",
       "plural" => false
        )
      )),
      new LoadsResource(array("silent" => false, "primary" => "GET", "id" => "id", "init" => array("callId"))),
      new SchemaResource(array("fields" => array("id", "time", "name"), "needs" => "id"))
    );
  }
}
