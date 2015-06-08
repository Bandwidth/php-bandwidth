<?php
/**
 * @class TimeoutCallEvent
 * https://catapult.inetwork.com/docs/callback-events/calltimeout-event/
 * 
 * Event triggered when a call's state
 * has not been changed since started
 */

namespace Catapult;

final class TimeoutCallEvent extends EventType {
    public function __construct() {
      $data = Ensure::Input(func_get_args(), Converter::toArray(json_decode(file_get_contents("php://input"))));
      parent::_init($data, new Call);
    }
}
