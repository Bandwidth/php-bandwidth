<?php
/**
 * @class DtmfCallEvent
 *
 * https://catapult.inetwork.com/docs/callback-events/dtmf-event/
 *
 * DtmfCallEvent is triggered whenever
 * dtmf is entered, it will provide the digits entered, 
 * the duration and time. All information
 * is kept within a call
 *
 *
 */
namespace Catapult;

final class DtmfCallEvent extends EventType {
  public function __construct() {
    $data = Ensure::Input(func_get_args(), Converter::toArray(json_decode(file_get_contents("php://input"))));

    parent::_init($data, new Call);
  }
}
