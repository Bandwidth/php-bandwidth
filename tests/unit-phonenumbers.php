<?php

/* Unit tests for Phone Numbers. These should
 * test the following functions
 * Comparatively to the credential unit tests
 * these run with no try/catching
 *
 *
 *
 * commands tested:
 * listNumbers/1
 * get/1
 * patch/1
 * allocate and create/1
 * listLocal
 * listTollFree
 * batchAllocateLocal
 * batchAllocateTollfree
 */

$cred = new Catapult\Credentials;
$client = new Catapult\Client($cred);


class PhoneNumbersTest extends PHPUnit_Framework_TestCase {
	public function testPhoneNumbersList()
	{
		$pn = new Catapult\PhoneNumbers;

		$phone_numbers = $pn->listNumbers();

		$this->assertTrue(sizeof($phone_numbers->get()) > 0);

	}

	public function testPhoneNumbersGet()
	{
		$pn = new Catapult\PhoneNumbers;
		$pn->get("number-id");

		$this->assertEquals($pn->id, "number-id");
	}

	public function testPhoneNumbersPatch()
	{
		$params = new Catapult\Parameters;
		$pn = new Catapult\PhoneNumbers("number-id");
		$params->setFallbackNumber(__DEFAULT_SENDER__);
		$pn->patch($params);

		$this->assertEquals($pn->fallbackNumber, __DEFAULT_SENDER__);
	}

	public function testPhoneNumbersAllocate()
	{
		$params = new Catapult\Parameters;
		$params->setNumber("+NUMBER");
		$params->setApplication("");
		$params->setFallback("");

		$pn = new Catapult\PhoneNumbers;

		$pn->allocate($params);
	}

	public function testListLocal()
	{
		$pn = new Catapult\PhoneNumbers;

		$numbers = $pn->listLocal();

		$this->assertTrue(sizeof($numbers) > 0);
	}

	public function testListTollfree()
	{
		$pn = new Catapult\PhoneNumbers;

		$numbers = $pn->listTollFree();

		$this->assertTrue(sizeof($numbers) > 0);

	}

	public function testBatchAllocateLocal()
	{
		$pn = new Catapult\PhoneNumbers;
		$params = new Catapult\Parameters;
		$params->setZip("20210");

		$numbers = $pn->batchAllocateLocal($params); 

		$this->assertTrue($numbers instanceof Catapult\PhoneNumbersCollection);
	}

	public function testBatchAllocateTollfree()
	{
		$pn = new Catapult\PhoneNumbers;
		$params = new Catapult\Parameters;
		$params->setZip("20210");

		$numbers = $pn->batchAllocateLocal($params); 

		$this->assertTrue($numbers instanceof Catapult\PhoneNumbersCollection);
	}

}

?>
