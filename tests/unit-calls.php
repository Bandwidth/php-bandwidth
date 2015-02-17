<?php

/* Unit tests for calls. These should
 * test the following functions
 * Comparatively to the credential unit tests
 * these run with little try/catching
 *
 *
 *
 * commands tested:
 * create/1
 * hangup/1
 * reject/1
 * get/1
 * list/0
 */

$cred = new Catapult\Credentials;
$client = new Catapult\Client($cred);


class CallTest extends PHPUnit_Framework_TestCase {
	public function testCall()
	{
		$params = new Catapult\Parameters;
		$params->setFrom(new Catapult\PhoneNumber(__DEFAULT_SENDER__));
		$params->setTo(new Catapult\PhoneNumber(__DEFAULT_RECEIVER__));

		$call = new Catapult\Call($params);

		$this->assertEquals($call->state, "started");
	}

	public function testWaitHangup()
	{
		$params = new Catapult\Parameters;
		$params->setFrom(new Catapult\PhoneNumber(__DEFAULT_SENDER__));
		$params->setTo(new Catapult\PhoneNumber(__DEFAULT_RECEIVER__));

		$call = new Catapult\Call;
		$call->create($params);


		$call->hangup();

		$this->assertEquals($call->check("state"), "completed");
	}

	public function testGetAndNotFound()
	{
		$params = new Catapult\Parameters;
		$params->setFrom(new Catapult\PhoneNumber(__DEFAULT_SENDER__));
		$params->setTo(new Catapult\PhoneNumber(__DEFAULT_RECEIVER__));

		$call = new Catapult\Call;

		try {
			$call->get("c-call-id");

			$this->assertTrue(FALSE);
		} catch (PHPUnit_Framework_AssertionFailedError $e) {
			$this->assertTrue(TRUE);
			return;
		}

	}

        /* needs awaiting
         * call
         */	
	public function testReject()
	{
		$params = new Catapult\Parameters;

		$params->setFrom(new Catapult\PhoneNumber(__DEFAULT_SENDER__));
		$params->setTo(new Catapult\PhoneNumber(__DEFAULT_RECEIVER__));


		$call = new Catapult\Call;
		$call->create($params);

		$call->reject();

		$this->assertEquals($call->check("state"), "rejected");
	}

	public function testAccept()
	{
		$call = new Catapult\Call;
		$calls = $call->listCalls();
		$last_incoming_call = $calls->find(array("direction" => "in"))->last();

		$last_incoming_call->accept();

		$call->accept();

		$call->wait();

		$this->assertEquals($call->check("state"), "active");
	}

	public function testDTMF()
	{
		$params = new Catapult\Parameters;
		$params->setFrom(new Catapult\PhoneNumber(__DEFAULT_SENDER__));
		$params->setTo(new Catapult\PhoneNumber(__DEFAULT_RECEIVER__));

		$call = new Catapult\Call;
		$call->create($params);

		$call->wait();

		$call->sendDtmf(new Catapult\DTMF("#123"));
	}

	public function testRecordings()
	{
		$params = new Catapult\Parameters; 
		$params->setFrom(new Catapult\PhoneNumber(__DEFAULT_SENDER__));
		$params->setTo(new Catapult\PhoneNumber(__DEFAULT_RECEIVER__));

		$call = new Catapult\Call;	

		$call->create($params);	

		$recordings = $call->get_recordings();

		/* we currently have no recordings */
		$this->assertEquals(sizeof($recordings->get()), 0);
	}

	/* initiate a call
	 * wait a set of time
	 * then transfer
	 */
	public function testTransfer()
	{
		$params = new Catapult\Parameters;
		$params->setFrom(new Catapult\PhoneNumber(__DEFAULT_SENDER__));
		$params->setTo(new Catapult\PhoneNumber(__DEFAULT_RECEIVER__));

		$call = new Catapult\Call;

		$call->create($params);
		
		$call->wait();

		$call->transfer(new Catapult\PhoneNumber(__DEFAULT_RECEIVER__));	
	}

	public function testTranscriptions()
	{
		$params = new Catapult\Parameters; 
		$params->setFrom(new Catapult\PhoneNumber(__DEFAULT_SENDER__));
		$params->setTo(new Catapult\PhoneNumber(__DEFAULT_RECEIVER__));

		$call = new Catapult\Call;	

		$call->create($params);
		
		$call->wait();

		$transcriptions = $call->get_transcriptions();

		$this->assertEquals(sizeof($transcriptions->get()), 0);
	}

	public function testEvent()
	{
		$params = new Catapult\Parameters; 
		$params->setFrom(new Catapult\PhoneNumber(__DEFAULT_SENDER__));
		$params->setTo(new Catapult\PhoneNumber(__DEFAULT_RECEIVER__));

		$call = new Catapult\Call;

		$call->create($params);
		
		$call->wait();

		$events = $call->get_events();

		/* we should have one event by now that is the creation */

		$this->assertEquals(sizeof($events->get()), 1);
	}

	/* Test a gather
	 * make sure to send it
	 * dmtfs before
	 * gathering
	 */
	public function testGather()
	{
		$params = new Catapult\Parameters;
		$params->setFrom(new Catapult\PhoneNumber(__DEFAULT_SENDER__));
		$params->setTo(new Catapult\PhoneNumber(__DEFAULT_RECEIVER__));

		$call = new Catapult\Call;	

		$call->create($params);

		$call->wait();

		$gather = new Catapult\Gather($call->id);

		$this->assertEquals($gather->id, NULL);	
	}

	public function testList()
	{
		$params = new Catapult\Parameters;
		$params->setPage(new Catapult\Page(0));
		$params->setSize(new Catapult\Size(200));

		$call = new Catapult\Call;	
		$calls = $call->listCalls();

		/* undeterministic length given the amount of calls however it should be over 1 by now */
		$this->assertTrue(sizeof($calls->get()) > 0);
	}

	public function testRefresh()
	{
		$params = new Catapult\Parameters;
		$params->setFrom(new Catapult\PhoneNumber(__DEFAULT_SENDER__));
		$params->setTo(new Catapult\PhoneNumber(__DEFAULT_RECEIVER__));

		$call = new Catapult\Call;	

		$call->create($params);

		$call->wait();

		/* wait 5 seconds then
	 	 * refresh
	 	 */
		$call->refresh();
	}

	public function testPlayAudio()
	{	
		$call = new Catapult\Call;
		$params = new Catapult\Parameters;

		$params->setFrom(new Catapult\PhoneNumber(__DEFAULT_SENDER__));
		$params->setTo(new Catapult\PhoneNumber(__DEFAULT_RECEIVER__));

		$call->create($params);

		$call->wait();

		$params->setLoopEnabled("true");
		$params->setTag(new Catapult\Tag("testing"));
		$params->setFileUrl("file.mp3");

		$call->play_audio("file.mp3", $params);
	}

	public function testStopAudio()
	{
		$call = new Catapult\Call;
		$params = new Catapult\Parameters;


		$params->setFrom(new Catapult\PhoneNumber(__DEFAULT_SENDER__));
		$params->setTo(new Catapult\PhoneNumber(__DEFAULT_RECEIVER__));

		$call->create($params);
		
		$call->stop_audio();
	}

	public function testSpeakSentence()
	{
		$params = new Catapult\Parameters;
		$call = new Catapult\Call;

		$params->setFrom(new Catapult\PhoneNumber(__DEFAULT_SENDER__));
		$params->setTo(new Catapult\PhoneNumber(__DEFAULT_RECEIVER__));

		$call->create($params);

		$params->setGender("female");
		$params->setVoice(new Catapult\Voice("Jorge"));
		$params->setLoopEnabled("true");
		$params->setSentence("hello");

		$call->speak_sentence($params);
	}
	
	public function testStopSentence()
	{
		$params = new Catapult\Parameters;
		$call = new Catapult\Call;

		$params->setFrom(new Catapult\PhoneNumber(__DEFAULT_SENDER__));
		$params->setTo(new Catapult\PhoneNumber(__DEFAULT_RECEIVER__));

		$call->create($params);

		$call->stopSentence($params);
	}

	public function testGatherCreate()
	{

		$call = new Catapult\Call;
		$params = new Catapult\Parameters;
		$params->setFrom(new Catapult\PhoneNumber(__DEFAULT_SENDER__));
		$params->setTo(new Catapult\PhoneNumber(__DEFAULT_RECEIVER__));

		$call->create($params);

		$gather = new Catapult\Gather($call->id);
		$params->setReason("max-digits");
		$params->setDigits("123");

		$gather->create($params);

		$this->assertEquals($gather->digits, "123");
	}

	public function testGatherGet()
	{

		$call = new Catapult\Call;
		$params = new Catapult\Parameters;
		$params->setFrom(new Catapult\PhoneNumber(__DEFAULT_SENDER__));
		$params->setTo(new Catapult\PhoneNumber(__DEFAULT_RECEIVER__));

		$call->create($params);

		$call->wait();
		$gather = new Catapult\Gather($call->id);
		$params->setReason("max-digits");
		$params->setDigits("123");

		$gather->create($params);

		$retgather = $gather->get($gather->id);
	
		$this->assertEquals($gather->digits, $retgather->digits);	
	}

	public function testGatherStop()
	{

		$call = new Catapult\Call;
		$params = new Catapult\Parameters;

		$params->setFrom(new Catapult\PhoneNumber(__DEFAULT_SENDER__));
		$params->setTo(new Catapult\PhoneNumber(__DEFAULT_RECEIVER__));

		$call->create($params);
		$gather = new Catapult\Gather($call->id);

		$params->setReason("max-digits");
		$params->setDigits("123");

		$gather->create($params);

		$gather->stop();

		$this->assertEquals($gather->state, Catapult\GATHER_STATES::completed);
	}

	public function testSIPCall()
	{
		$call = new Catapult\Call;
		$params = new Catapult\Parameters;
		$call->setFrom(new Catapult\PhoneNumber(__DEFAULT_SENDER__));
		$call->setTo(new Catapult\SIP(__DEFAULT_SIP___));
		$call->create($params);
	}
	
	public function testBridge()
	{
		$call = new Catapult\Call;
        $params = new Catapult\Parameters;

		$params->setFrom(new Catapult\PhoneNumber(__DEFAULT_SENDER__));
		$params->setTo(new Catapult\PhoneNumber(__DEFAULT_RECEIVER__));

		$call->create($params);

		$call1 = new Catapult\Call;

		$call1->create($params);

		$call1->wait();

		$call->create($params);

		$call->wait();

		$bridge = new Catapult\Bridge;	

		$bridge->create(array(
			"callIds" => Catapult\CallCombo::Make($call, $call1)
		));
	}

}
?>

