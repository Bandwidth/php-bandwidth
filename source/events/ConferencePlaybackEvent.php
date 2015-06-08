<?php
/**
 * @class ConferencePlaybackEvent
 * 
 * https://catapult.inetwork.com/docs/callback-events/conference-playback-event/
 *  
 * Playback events for conferences
 */ 
namespace Catapult;

final class ConferencePlaybackEvent extends EventType {
	public function __construct() {
		$data = Ensure::Input(func_get_args(), Converter::toArray(json_decode(file_get_contents("php://input"))));

		parent::_init($data, new Conference);
	}
}
