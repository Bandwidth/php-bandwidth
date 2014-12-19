<?php

/* Unit tests for conferences. These should
 * test the following functions
 * Comparatively to the credential unit tests
 * these run with little try/catching
 *
 *
 *
 * commands tested:
 * add_member/2
 * create/1
 * update/1
 * get_members/2
 */

$cred = new Catapult\Credentials;
$client = new Catapult\Client($cred);


class ConferenceTest extends PHPUnit_Framework_TestCase {
	public function testCreateConference()
	{
		$params = new Catapult\Parameters;
		$params->setFrom(new Catapult\PhoneNumber(__DEFAULT_SENDER__));

		$conference = new Catapult\Conference;
		$conference->create($params);

		$this->assertTrue($conference->id != null);
	}

	public function testGet()
	{
		$params = new Catapult\Parameters;
		$params->setFrom(new Catapult\PhoneNUmber(__DEFAULT_SENDER__));

		$conference = new Catapult\Conference;
		$conference->create($params);

		$retconf = $conference->get($conference->id);

		$this->assertEquals($retconf->id, $conference->id);
	}
	
	public function testUpdate()
	{
		$params = new Catapult\Parameters;
		$params->setFrom(new Catapult\PhoneNumber(__DEFAULT_SENDER__));

		
		$conference = new Catapult\Conference;
		$conference->create($params);

		$params->setFrom(new Catapult\PhoneNumber(__DEFAULT_RECEIVER__));

		$conference->update($params);

		$this->assertEquals($conference->from, __DEFAULT_RECEIVER__);
	}

	public function testAddMember()
	{
		$conference = new Catapult\Conference;
		$params = new Catapult\Parameters;
		$params->setFrom(new Catapult\PhoneNumber(__DEFAULT_SENDER__));

		$conference->create($params);

		$call = new Catapult\Call;

		$params->setFrom(new Catapult\PhoneNumber(__DEFAULT_SENDER__));
		$params->setTo(new Catapult\PhoneNumber(__DEFAULT_RECEIVER__));
		$params->setConferenceId($conference->id);

		$call->create($params);

		$call->wait();


		$conference->add_member(array(
			"callId" => $call->id,
			"joinTone" => "false",
			"leavingTone" => "false"
		));

		$members = $conference->getMembers();

		$this->assertEquals(sizeof($members->get()), 1);
	}

	public function testUpdateMember()
	{
		$params = new Catapult\Parameters;

		$conf = new Catapult\Conference;

		$params->setFrom(new Catapult\PhoneNumber(__DEFAULT_SENDER__));

		$conf->create($params);

		$call = new Catapult\Call;

		$params->setFrom(new Catapult\PhoneNumber(__DEFAULT_SENDER__));
		$params->setTo(new Catapult\PhoneNumber(__DEFAULT_RECEIVER__));
		$params->setConferenceId($conf->id);

		$call->create($params);

		$call->wait();

		$params->setJoinTone("false");
		$params->setLeavingTone("false");
		$params->setCallId($call->id);

		$member = $conf->add_member($params);

		$params->setMute("true");

		$member->update($params);

		$params->setHold("true");

		$member->update($params);
	}

	public function testMuteMember()
	{
		$params = new Catapult\Parameters;

		$params->setFrom(new Catapult\PhoneNumber(__DEFAULT_SENDER__));

		$conf = new Catapult\Conference($params);

		$params->setFrom(new Catapult\PhoneNumber(__DEFAULT_SENDER__));
		$params->setTo(new Catapult\PhoneNumber(__DEFAULT_RECEIVER__));

		$call->create($params);

		$call->wait();	

		$params->setJoinTone("false");
		$params->setLeavingTone("false");
		$params->setCallId($call->id);

		$params->setMute("mute");

		$member = $conf->addMember($params);

		/* is the member muted */
		$this->assertEquals($member->mute, "true");
	}

	public function testGetMembers()
	{
		$params = new Catapult\Parameters;
		$conference = new Catapult\Conference;

		$params->setFrom(new Catapult\PhoneNumber(__DEFAULT_SENDER__));

		$conference->create($params);
		
		$call = new Catapult\Call;

		$params->setFrom(new Catapult\PhoneNumber(__DEFAULT_SENDER__));
		$params->setTo(new Catapult\PhoneNumber(__DEFAULT_RECEIVER__));
		$params->setConferenceId($conf->id);


		$call->create($params);

		$call->wait();

		/* add five slots */
		for($i = 0; $i != 5; $i ++) {
			$params->setCallId($call->id);
			$params->setJoinTone("false");
			$params->setLeavingTone("false");
			$conference->add_member($params);
		}

		$members = $conf->get_members();

		$this->assertTrue(sizeof($members->get()) == 5);
	}

	public function testGetMember()
	{
		$params = new Catapult\Parameters;
		$params->setFrom(new Catapult\PhoneNumber(__DEFAULT_SENDER__));
		$call = new Catapult\Call;
		$conference = new Catapult\Conference;

		$conference->create($params);

		$params->setFrom(new Catapult\PhoneNumber(__DEFAULT_SENDER__));
		$params->setTo(new Catapult\PhoneNumber(__DEFAULT_RECEIVER__));
		$params->setConferenceId($conference->id);

		$call->create($params);

		$call->wait();

		$params->setLeavingTone("false");
		$params->setCallId($call->id);
		$params->setJoinTone("false");

		$member = $conference->add_member($params);

		$member = $conference->member($member->id);

		$this->assertTrue($member->id);
	}

	public function testPlayAudio()
	{
		$params = new Catapult\Parameters;
		$params->setFrom(new Catapult\PhoneNumber(__DEFAULT_SENDER__));
		$call = new Catapult\Call;
		$conference = new Catapult\Conference;

		$conference->create($params);

		$params->setFrom(new Catapult\PhoneNumber(__DEFAULT_SENDER__));
		$params->setTo(new Catapult\PhoneNumber(__DEFAULT_RECEIVER__));
		$params->setConferenceId($conference->id);

		$call->create($params);

		$call->wait();

		$params->setLeavingTone("false");
		$params->setCallId($call->id);
		$params->setJoinTone("false");

		$member = $conference->add_member($params);

		$params->setFileUrl(new Catapult\MediaUrl(""));

		$member->play_audio($params);
	}

	public function testStopAudio()
	{
		$params = new Catapult\Parameters;
		$params->setFrom(new Catapult\PhoneNumber(__DEFAULT_SENDER__));
		$call = new Catapult\Call;
		$conference = new Catapult\Conference;

		$conference->create($params);

		$params->setFrom(new Catapult\PhoneNumber(__DEFAULT_SENDER__));
		$params->setTo(new Catapult\PhoneNumber(__DEFAULT_RECEIVER__));
		$params->setConferenceId($conference->id);

		$call->create($params);

		$call->wait();

		$member = $conference->add_member(array(
			"callId" => $call->id,
			"setJoinTone" => "false"
		));

		$member->stop_audio();
	}
	
}

?>
