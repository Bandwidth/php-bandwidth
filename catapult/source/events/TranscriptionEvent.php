<?php
/**
 * @class TranscriptionCallEvent
 *
 * https://catapult.inetwork.com/docs/callback-events/transcription-event/
 *
 * Direct access to a transcription and its 
 * recording
 */

namespace Catapult;

class TranscriptionCallEvent extends EventType {
    /**
     * TranscriptionCallEvent like GatherEvent
     * loads differently we first pass
     * the recording id. Parent will get the
     * model.
     */
    public function __construct()
    {
      $data = Ensure::Input(func_get_args(), Converter::toArray(json_decode(file_get_contents("php://input"))));
      parent::_init($data, new Transcription);
    }
}
