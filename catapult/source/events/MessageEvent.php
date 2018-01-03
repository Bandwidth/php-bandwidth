<?php
/**
 * @class MessageEvent
 * https://catapult.inetwork.com/docs/callback-events/
 * 
 * Provide an event class for Messages, SMS and MMS 
 * this includes:
 * 
 * incomingMessageEvents
 * outgoingMessageEvents
 *
 */
namespace Catapult;

final class MessageEvent extends EventType {
    public function __construct()
    {
      $data = Ensure::Input(func_get_args(), Converter::toArray(json_decode(file_get_contents("php://input"))));
      parent::_init($data, new Message); 
    }
}
