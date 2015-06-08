<?php
/**
 * @class CallEvent
 *
 * https://catapult.inetwork.com/docs/callback-events/
 *
 * Call event. Provide base
 * functionality to create a call from
 * the event. The call @model should be 
 * made directly, information being of the event's
 *
 * CallEvents include:
 * answers,
 * rejections,
 * DTMF 
 */

namespace Catapult;

class CallEvent extends EventType {
    /**
     * Call events get the following
     * Init Forms:
     *
     * CallEvent
     * CallEvent(array)
     */
    public function __construct()
    {
      $data = Ensure::Input(func_get_args(), Converter::toArray(json_decode(file_get_contents("php://input"))));

      return parent::_init($data, new Call);
    }	
}
