<?php

/* Unit tests for messages / multimessages. These should
 * test the following functions
 * Comparatively to the credential unit tests
 * these run with no try/catchig
 *
 *
 *
 * commands tested:
 * list_messages/2
 * send/2
 * /1
 * get_members/2
 */

$cred = new Catapult\Credentials;
$client = new Catapult\Client($cred);


class MessageTest extends PHPUnit_Framework_TestCase {
	public function testMessage()
	{
		$params = new Catapult\Parameters;
		$params->setFrom(new Catapult\PhoneNumber(__DEFAULT_SENDER__));
		$params->setTo(new Catapult\PhoneNumber(__DEFAULT_RECEIVER__));
		$params->setText(new Catapult\TextMessage("Unit test 1.." . __FUNCTION__));

		$message = new Catapult\Message;

		$message->send($params);
	}

	public function testListMessages()
	{
		$params = new Catapult\Parameters;
		$params->setPage(new Catapult\Page(0));
		$params->setSize(new Catapult\Size(50));

		$message = new Catapult\Message;

		$message->list_messages($params);
	}
		
	public function testTimeoutMessage()
	{
		$params = new Catapult\Parameters;

		$params->setCallbackTimeout(new Catapult\Timeout(5));
		$params->setFrom(new Catapult\PhoneNumber(__DEFAULT_SENDER__));
		$params->setTo(new Catapult\PhoneNumber(__DEFAULT_RECEIVER__));

		$params->setText(new Catapult\TextMessage("Unit test 1.." . __FUNCTION__));

		$message = new Catapult\Message;

		$message->send($params);

	}

	public function testMultiple()
	{
		/* send multiple
		 * messages
		 */

		printf("Testing multiple messages..\n");
		$msgs = new Catapult\MessageMulti();
		$from = new Catapult\PhoneNumber(__DEFAULT_SENDER__);
		$to = new Catapult\PhoneNumber(__DEFAULT_RECEIVER__);
		$callback = new Catapult\Callback("http://google.com/");

		$message1 = new Catapult\TextMessage("Testing with Catapult PHP API. Call 1..");
		$message2 = new Catapult\TextMessage("Testing with Catapult PHP API. Call 2..");
		$message3 = new Catapult\TextMessage("Testing with Catapult PHP API. Call 3..");

		$msgs->pushMessage($from, $to, $message1, $callback);
		$msgs->pushMessage($from, $to, $message2, $callback);
		$msgs->pushMessage($from, $to, $message3, $callback);

		$messages = $msgs->execute();
	}

}

?>
