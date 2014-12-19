<?php

/* Unit tests for bridge. These should
 * test the following functions
 * Comparatively to the credential unit tests
 * these run with no try/catching
 * !IMPORTANT: calls unit test contants
 * one bridge test. Merge here in future
 *
 * commands tested:
 * create/1
 * list/1
 * getCalls/1
 */

$cred = new Catapult\Credentials;
$client = new Catapult\Client($cred);


class BridgeTest extends PHPUnit_Framework_TestCase {
	public function testBridgeCreate()
	{
		$params = new Catapult\Parameters;
		$call = new Catapult\Call;

		$params->setFrom(new Catapult\PhoneNumber(__DEFAULT_SENDER__));
		$params->setTo(new  Catapult\PhoneNumber(__DEFAULT_RECEIVER__));

		$call->create($params);

		$call->wait();

		$call1 = new Catapult\Call;


		$params->setFrom(new Catapult\PhoneNumber(__DEFAULT_SENDER__));
		$params->setTo(new  Catapult\PhoneNumber(__DEFAULT_RECEIVER__));

		$call1->create($params);

		$call1->wait();

		$bridge = new Catapult\Bridge;	

		$bridge->create(array(
			"callIds" => Catapult\CallCombo::Make($call, $call1)
		));

	}	

	public function testBridgeGetCalls()
	{
		$bridge = new Catapult\Bridge;

		$calls = $bridge->getCalls();
	}

	public function testBridgeGet()
	{
		$bridge = new Catapult\Bridge;
		$bridge->get("b-id");
	}
}

?>
