<?php

namespace Catapult;

/**
 * An interface for Catapult Bandwidth events
 * these objects will be called whenever
 * an event is sent from the Catapult api
 */
interface Events {
	const CALL_REJECTED = 1;
	const UNSPECIFIED = 2;
	const NORMAL_CLEARING = 3;
	const USER_BUSY = 4;
	const NORMAL_UNSPECIFIED = 5;
	const NORMAL_CIRCUIT_CONGESTION = 6;
	const SWITCH_CONGESTION = 7;
}

/**
 * Namespace for absolving where to put
 * an arbitrary event. All namespaces
 * should be valid events as defined by
 * Catapult ApiDocs.
 */
abstract class EventNamespace {
	public static $call = array(
		"incoming",
		"hangup",
		"answer",
		"reject",
		"speak",
		"recording",
		"dtmf",
		"gather"
	);
	public static $conference = array(
		"conference",
		"conference-member",
		"conference-speak",
		"conference-playback"
	);

	public static $message = array(
		"sms"
	);
}


/**
 * Primary event object. All events
 * should provide its basic
 * methods. Moreover be initialized
 * by it.  
 */
class Event {
	public function __construct($data=null)
	{
		$data = json_decode($data);

		return new EventType(Cleaner::Omit(Converter::ToArray($data)));
	}
}

/**
 * A generic to handle all event types. Afterwards
 * it should initiate the type specified in datapacket
 * 
 * EventType($data) where data
 *
 * i.e
 * data:
 * {
 *    "eventType": "sms"   
 * }
 * makes MessageEvent
 * 
 */
class EventType extends Event {
	public function __construct($args)
	{ 
		if (!(array_key_exists("eventType", $args)))
			throw new \CatapultApiException("Events NEED property 'eventType'");

		$event = $args['eventType'];
		$splits = explode("-", $event);
		$class = __CLASS__;
		
		/**
	 	 * for calls delegate
	 	 * to the needed class
	 	 * by prepended 'Call'
	 	 */
		if (in_array($event, EventNamespace::$call)) {
			$class = Constructor::Find("Catapult\\" . "Call" . ucwords($events));
			return new $class($args);
		}


		/**
	         * for conference
	 	 * take out the hypens
	         * and camelcase
	 	 */
		if (in_array($event, EventNamespace::$conference)) {
			$class = Constructor::Find("Catapult\\" . "Conference" . CamelCase($splits));
			return new $class($args);
		}
				

		/**
		 * Message only has
	 	 * one event. 
		 * Use this class
		 */
		if (in_array($event, EventNamespace::$message))
			return new MessageEvent($args);

		throw new \CatapultApiException("EventType was not found in list of events");
	}

	public function __toString()
	{
		return '';
	}
}

/**
 * Call event. Provide base
 * functionality to create a call from
 * the event.
 */
class CallEvent extends EventType {
	public function __construct()
	{
		return new Call($this->call_id);
	}	
}

class ConferenceEventMixin extends CallEvent {
	public function __construct()
	{
		return new Conference($this->conference_id);
	}
}


final class MessageEvent extends EventType {
	public function __construct()
	{
		return new Message($this->message_id);
	}
}



/**
 * Defines a set of extensions
 * that are used throughout
 * the events. Normally they are
 * called when their event type is
 * specified. 
 */
final class IncomingCallEvent extends EventType {
}

final class AnswerCallEvent extends CallEvent {

}

final class HangupCallEvent extends CallEvent {

}

final class RejectCallEvent extends CallEvent {

}

final class PlaybackCallEvent extends CallEvent {

}

final class GatherCallEvent extends CallEvent {
}

final class DtmfCallEvent extends CallEvent {

}

final class SpeakCallEvent extends CallEvent {

}

final class ErrorCallEvent extends CallEvent {

}

final class TimeoutCallEvent extends CallEvent {

}

final class RecordingCallEvent extends CallEvent {

}

final class ConferenceEvent extends ConferenceEventMixin {

}

final class ConferenceMemberEvent extends ConferenceEventMixin {

}

final class ConferencePlaybackEvent extends ConferenceEventMixin {

}

final class ConferenceSpeakEvent extends ConferenceEventMixin {

}

?>
