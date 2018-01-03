<?php
/**
 * @class SpeakCallEvent
 *
 * https://catapult.inetwork.com/docs/callback-events/speak-event/
 *
 * Provides information on a speaking for a call. References 
 * call object with information of 'tag' used fo rspeak
 *
 */
namespace Catapult;

final class SpeakCallEvent extends EventType {
    public function __construct() {
      $data = Ensure::Input(func_get_args(), Converter::toArray(json_decode(file_get_contents("php://input"))));
      parent::_init($data, new Call);
    }
}
