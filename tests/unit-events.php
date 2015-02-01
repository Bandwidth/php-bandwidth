<?php

/* Unit tests for events. These should
 * test the following functions
 * Comparatively to the credential unit tests
 * these run with no try/catching
 *
 *
 *
 * commands tested:
 * all events
 */

$cred = new Catapult\Credentials;
$client = new Catapult\Client($cred);


class EventsTest extends PHPUnit_Framework_TestCase {
	public function testFactoryIncoming()
	{
		$data = json_encode(array(
			"eventType" => "incoming",
			"from" => __DEFAULT_SENDER__,
			"to" => __DEFAULT_RECEIVER__
		));

		$event = new Catapult\Event($data);

		$this->assertTrue($event->eventType, "incoming");
	}	

	public function testFactoryAnswer()
	{
		$data = json_encode(array(
			"eventType" => "answer",
			"from" => __DEFAULT_SENDER__,
			"to" => __DEFAULT_RECEIVER__
		));

		$event = new Catapult\Event($data);

		$this->assertEquals($event->eventType, "answer");
	}

	public function testFactoryHangup()
	{
		$data = json_encode(array(
			"eventType" => "hangup",
			"from" => __DEFAULT_SENDER__,
			"to" => __DEFAULT_RECEIVER__
		
		));

		$event = new Catapult\Event($data);

		$this->assertEquals($event->from, __DEFAULT_SENDER__);
		$this->assertEquals($event->to, __DEFAULT_RECEIVER__);
	}

	public function testFactoryReject()
	{
		$data = json_encode(array(	
			"eventType" => "hangup",
			"from" => __DEFAULT_SENDER__,
			"to" => __DEFAULT_RECEIVER__));

		$event = new Catapult\Event($data);
	}

	public function testFactoryPlayback()
	{
		$data = json_encode(array(
			"eventType" => "playback"
		));

		$event = new Catapult\Event($data);

		$this->assertTrue($event->eventType, "playback");
	}

	public function testFactoryGather()
	{
		$data = json_encode(array(
			"state" => "completed",
			"eventType" => "gather",
			"gatherId" => ""
		));

		$event = new Catapult\Event($data);
	}

	public function testFactoryError()
	{
		$data = json_encode(array(
			"to" => "",
			"eventType" => "error"
		));

		$event = new Catapult\Event($data);
	}

	public function testFactoryTimeout()
	{
		$data = json_encode(array(
			"eventType" => "timeout"
		));

		$event = new Catapult\Event($data);
	}

	public function testFactoryRecording()
	{
		$data = json_encode(array(
			"eventType" => "recording"
		));


		$event = new Catapult\Event($data);
	}

	public function testFactorySpeak()
	{
		$data = json_encode(array(
			"eventType" => "speak"
		));

		$event = new Catapult\Event($data);
	}

	public function testFactoryDtmf()
	{
		$data = json_encode(array(
			"eventType" => "dtmf"
		));


		$event = new Catapult\Event($data);
	}

	public function testFactorySms()
	{
		$data = json_encode(array(
			"eventType" => "sms"
		));

		$event = new Catapult\Event($data);
	}

	public function testFactoryConference()
	{
		$data = json_encode(array(
			"eventType" => "conference"
		));

		$event = new Catapult\Event($data);
	}

	public function testFactoryConferenceMember()
	{
		$data = json_encode(array(
			"eventType" => "conference"
		));

		$event = new Catapult\Event($data);
	}

	public function testFactoryConferenceSpeak()
	{
		$data = json_encode(array(
			"eventType" => "conference-speak",
			"conferenceId" => ""
		));

		$event = new Catapult\Event($data);
	}

	public function testFactoryConferencePlayback()
	{
		$data = json_encode(array(
			"eventType" => "conference-playback"
		));

		$event = new Catapult\Event($data);
	}

	public function testUnknownEvent()
	{
		$data = json_encode(array(
			"eventType" => "unknown"
		));

		/** should throw an error **/
		$event = new Catapult\Event($data);
	}
}

?>
