<?php

/* Unit tests for number info. These should
 * test the following functions
 * Comparatively to the credential unit tests
 * these run with no try/catching
 *
 *
 *
 * commands tested:
 * get/1
 */

$cred = new Catapult\Credentials;
$client = new Catapult\Client($cred);


class NumberInfoTest extends PHPUnit_Framework_TestCase {
	public function testNumberInfoGet()
	{
		$number = new Catapult\NumberInfo;

		$number->get(__DEFAULT_SENDER__);

		$this->assertEquals($number->number, __DEFAULT_SENDER__);
	}

}

?>
