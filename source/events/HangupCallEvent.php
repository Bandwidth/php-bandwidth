<?php
/**
 * @class HangupCallEvent
 *
 * Provides information on a hung up Call
 *
 */
namespace Catapult;
final class HangupCallEvent extends EventType {
  
  /**
   * CTor for the hangup
   * call event should
   * only accept 'hangup'
   */
  public function __construct() {
    $data = Ensure::Input(func_get_args(), Converter::toArray(json_decode(file_get_contents("php://input"))));

    return parent::_init($data, new Call);
  }
}
