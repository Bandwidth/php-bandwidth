<?php
/**
 * @class RecordingCallEvent
 *
 * https://catapult.inetwork.com/docs/callback-events/recording-event/
 *
 * Recording call events should initialize a recording model
 * and provide access to transcriptions
 *
 */



namespace Catapult;

class RecordingCallEvent extends EventType {
    public function __construct()
    {
      $data = Ensure::Input(func_get_args(),Converter::toArray(json_decode(file_get_contents("php://input"))));

      parent::_init($data, new Recording);
    }	
}
