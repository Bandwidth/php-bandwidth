<?php

require_once("Catapult.php");

/* Credentials found in
 * credential.json.
 * if not found it will use envorinment
 * variables.
 */
$credentials = new Catapult\Credentials();

echo <<<EOF
	Hi, your api credentials are
	User Id => {$credentials->get("BANDWIDTH_USER_ID")}
	API Key => {$credentials->get("BANDWIDTH_API_TOKEN")}
	API Secret => {$credentials->get("BANDWIDTH_API_SECRET", FALSE)}
	Your registered numbers (as defined): {$credentials->get("BANDWIDTH_VALID_NUMBERS")}

	Now invoking a test messages. Standby.. 
	\n
	\n
EOF;

/* client will 'only' throw a warning
 * on missing input. And will not attempt to authenticate
 * all authentication done on calls as Python version
 */
$client = new Catapult\Client($credentials);

try {

	$params = new Catapult\Parameters;

	$params->setFrom(new Catapult\PhoneNumber(__DEFAULT_SENDER__));
	$params->setTo(new Catapult\PhoneNumber("+15"));

	$call = new Catapult\Call($params);

	$call->wait();

	$gather = new Catapult\Gather($call);

	$gather->create(array(
		"maxDigits" => "5",
		"terminatingDigits" => "*",
		"interDigitTimeout" => "7",
		"prompt" => array(
			"sentence" => "Please enter your 5 digit code"
		)
	));

	sleep(10);
	$gather->reload();

	if ($gather->digits == "11111")
		$call->speakSentence(array(
			"sentence" => "Corrent. Your in!"
		));
	else
		$call->speakSentence(array(
			"sentence" => "the code you enetered is wrong please try again"
		));



	die;

	$bridge = new Catapult\Bridge;

	$bridge->create(array(
		"callIds" => Catapult\CallCombo::Make($call1, $call2)
	));	



	$bridge_calls = $bridge->getCalls();

	$all_bridges = $bridge->listBridges();

	die;

	printf("great. call was picked up. Sending a dtmf");
	$call1->sendDtmf("#123");

	$gather = new Catapult\Gather($call1->id);

	$gather->create(array(
		"maxDigits" => 10
	));
	$gather->stop();

	echo $gather->state;
	die;

	$params->setCallId($call1->id);
	$params->setJoinTone("true");
	$params->setLeaveTone("true");

	while (true)
		if ($call1->check("state", Catapult\CALL_STATES::active))
			$conf->addMember($params);
		else
			printf("waiting for call to be active.. \n\n");

	
	printf("added member");	
	die;

	echo $message->check("state", "received") ? 'received': 'not yet..';

	echo $message->state;

			
	echo "\n\n\n";

	die;

	$call1->hangup();



	die;
	

	/* make sure both
	 * calls are in active
	 * state
	 */


	/* create a bridge between both calls */

	/* send a dtmf */



	/* wait 10 seconds */
	sleep(10);


	/* hangup */
	$call->hangup();

	// this borrows from test_message_events.py
	// as per the Python Implementation	

	// now create a message
	// for a demo
        $from = new Catapult\PhoneNumber("+12135147675");
	$to = new Catapult\PhoneNumber("+13156594693");
	$txt = new Catapult\TextMessage(isset($argv[1]) ? (string) $argv[1] : "Hello. Testing from catipult PHP API.");
	$callback = new Catapult\Callback("http://bandwidth.com/");

	printf("Sending one message..\n");
	$msg = new Catapult\Message();
	$msg->send($from, $to, $txt, $callback /* optional */);

	printf("State of message => %s\n", $msg->state);
	printf("Text was: %s\n\n", (string) $txt);

	// using the same
	// data. verify if
	// it is in the message list

	printf("Fetching last sent..\n");
	$cmsgs = new Catapult\Message();
	$msgs = $cmsgs->list_messages(array('from' => $from, 'to' => $to));
	$messages = new Catapult\MessageMulti($msgs);
	printf("Last sent message was => \nText: %s\n\n", $messages->last()->text);

	/* send a message (using same number) 
	 * with a timeout
	 * timeout
	 */

	$timeout = new Catapult\Timeout(5);

	printf("Sending message with a callbackTimeout of %s milliseconds\n\n", $timeout);
	$txt1 = "Testing catapult PHP api with a callback timeout of " . $timeout;
	$msg->send($from, $to, $txt1, $callback, $timeout);

	/* send multiple
	 * messages
	 */

	printf("Testing multiple messages..\n");
	$msgs = new Catapult\MessageMulti();
	$message1 = new Catapult\TextMessage("Testing with Catapult PHP API. Call 1..");
	$message2 = new Catapult\TextMessage("Testing with Catapult PHP API. Call 2..");
	$message3 = new Catapult\TextMessage("Testing with Catapult PHP API. Call 3..");

	$msgs->push_message($from, $to, $message1, $callback);
	$msgs->push_message($from, $to, $message2, $callback);
	$msgs->push_message($from, $to, $message3, $callback);

	$messages = $msgs->execute();

	printf("Multiple message result: ");
	printf("%s\n\n", var_dump($messages));

	/* Test a simple
	 * message_event. Type should
	 * be MessageEvent and EventType
	 */
	printf("Testing message message_events..\n\n");
	$data = array("eventType"=> "sms",
                    "direction"=> "in",
                    "messageId"=> "m-xx",
                    "messageUri"=> "https://api.catapult.inetwork.com/v1/",
                    "from"=> (string) $from,
                    "to"=> (string) $to,
                    "text"=> "Example",
                    "applicationId"=> $credentials->get("API_APPLICATION_ID"),
                    "time"=> "2012-11-14T16:13:06.076Z",
                    "state"=> "received");
	$event = new Catapult\Event();
	$message_event = $event->create($data);

	/* normally PHPUnit here.
	 * seek to add test case scenarios
	 */

	asserte($message_event instanceof Catapult\MessageEvent);
	asserte($message_event instanceof Catapult\EventType);

	asserte($message_event->direction == "in");
	asserte($message_event->messageId === "m-xx");
	asserte($message_event->to == (string) $to);
	asserte($message_event->text == "Example");
	asserte($message_event->state == "received");
	asserte($message_event->message instanceOf Catapult\Message);
	asserte($message_event->message->id == "m-xx");

	printf("MessageEvent tests passed..\n\n");


	printf("Testing calls..\n\n");

	$call = new Catipult\Call(array(
		"from" => "303",
		"to" => "303",
		"callTimeout" => 3600,
		"bridgeId" => NULL,
		"resordingEnabled" => NULL,
		"callbackUrl" => "google.com"	
	));
	$call->create();


} catch (\CatapultApiException $e) {
/* Something went
 * wrong. Figure
 * out the warning
 * here. Could be connection
 * or call related
 */
  printf($e->getResult());

}

?>
