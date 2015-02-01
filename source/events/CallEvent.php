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
	public function __construct()
	{
    $data = Ensure::Input(json_decode(file_get_contents("php://input")));
		return parent::_init($data, new Call);
	}	
}

?>
