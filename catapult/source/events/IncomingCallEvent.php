<?php
/**
 * @class IncomingCallEvent
 *
 * https://catapult.inetwork.com/docs/callback-events/incoming-call-event/
 *
 * Subclass of callevent property 'direction' will always
 * be incoming. 
 */
namespace Catapult;

final class IncomingCallEvent extends EventType {
    public function __construct() {
      $data = Ensure::Input(func_get_args(), Converter::toArray(json_decode(file_get_contents("php://input"))));

      parent::_init($data, new Call);
    }
}
