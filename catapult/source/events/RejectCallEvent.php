<?php
/**
 * @class RejectCallEvent
 * https://catapult.inetwork.com/docs/callback-events/reject-event/
 * 
 * information on a call rejection
 */

namespace Catapult;

final class RejectCallEvent extends EventType {
    public function __construct() {
      $data = Ensure::Input(func_get_args(), Converter::toArray(json_decode(file_get_contents("php://input"))));
      parent::_init($data, new Call);
    }
}
