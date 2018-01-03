<?php
/**
 * @class ErrorCallEvent
 *
 * Provides information on Call Errors
 */

namespace Catapult;
final class ErrorCallEvent extends EventType {

    /**
     * CTor for Error call event
     * by default should signal
     * that we have a bad call, 
     * more information is 
     *
     * is available in the property
     * state
     *
     */
    public function __construct() {
      $data = Ensure::Input(func_get_args(), Converter::toArray(json_decode(file_get_contents("php://input"))));

      parent::_init($data, new Call);
    }
}
