<?php
/**
 * @class GatherEvent
 * https://catapult.inetwork.com/docs/callback-events/gather-event/
 * 
 * Gather event should have link to its
 * call as well as gather.
 * 
 * arguments are usually passed in the form:
 * GatherEvent('call-id', 'gather-id')
 */
namespace Catapult;

final class GatherCallEvent extends EventType {
    /**
    * Handle things a little different
    * gather will silently load
    * we need to pass the callId first
    * parent will call get/1 with the GatherId
    */
    public function __construct() {
      $data = Ensure::Input(func_get_args(),Converter::toArray(json_decode(file_get_contents("php://input"))));

      parent::_init($data, new Gather);
    }
}
