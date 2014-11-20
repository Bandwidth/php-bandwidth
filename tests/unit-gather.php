<?php

/* Unit tests for gather. These should
 * test the following functions
 * Comparatively to the credential unit tests
 * these run with no try/catching
 * NOTE: unit-calls has one gather
 * test. This will add to it
 *
 *
 * commands tested:
 * create/1
 * get/1
 */

$cred = new Catapult\Credentials;
$client = new Catapult\Client($cred);


class GatherTest extends PHPUnit_Framework_TestCase {
	public function testGatherCreate()
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
		$this->assertEquals($gather->digits, "123");
	}	

	public function testGatherGet()
	{
		$gather = new Catapult\Gather;
		$gather->get("g-id");

		$this->assertEqual($gather->id, "g-id");
	}

}

?>
