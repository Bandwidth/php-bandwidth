<?php

namespace Catapult;
/* message based demonstration for catapult api in php
 */

/* Event object as per Python
 * SDK. Here only for demo in test.php
 *
 * events should be syncronized
 * to reception and sending. 
 */

$_events = array(
	MessageEvent
);

class EventType extends Event {
	/* set all the events
	 * data.
	 */
	public function __construct($args)
	{ 
		foreach($args as $k => $arg) 
			$this->{$k} = $arg;	
	}
}

final class MessageEvent extends EventType {
	public static $eventType = NULL;
	public static $direction = NULL;
	public static $messageId = NULL;
	public static $form = NULL;
	public static $to = NULL;
	public static $text = NULL;
	public static $applicationId = NULL;
	public static $time = NULL;
	public static $state = NULL;
	public static $tag = NULL;

	public function __construct($args)
	{
		parent::__construct($args);

		$this->message();
	}

	public function __toString()
	{
		return str_replace("?", $this->message_id, "MessageEvent(message_id=?)");
	}

	public function message()
	{
		return ($this->message = new Message(array('id' => $this->messageId, 'state' => $this->state)));
	}
}

class Event {
	public static $events = _events;

	public function __construct($data=array(), $args=array())
	{ /* needs implementation */ }

	/* stub goto messageevent */
	public function create($data)
	{
		if (!isset($data['eventType']))
			throw new \CatipultApiException("Event not set");
		

		return new MessageEvent($data);
	}
}

?>
