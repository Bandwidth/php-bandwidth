<?php
/*
 * @class AnswerCallEvent
 * https://catapult.inetwork.com/docs/callback-events/answer-event/
 *
 * Event when a call has been picked up
 */

namespace Catapult;

final class AnswerCallEvent extends EventType {
    public function __construct() {
      $data = Ensure::Input(func_get_args(), Converter::toArray(json_decode(file_get_contents("php://input"))));

      parent::_init($data, new Call);
    }
}
