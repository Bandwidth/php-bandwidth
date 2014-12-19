<?php

/**
 * Unit tests for helper functions/utils 
 * 
 * classes tested
 * EnsureResource
 * URIResource
 * ResolverResource
 * utils
 */

$cred = new Catapult\Credentials;
$client = new Catapult\Client($cred);


class CoreTest extends PHPUnit_Framework_TestCase {
	public function testURIResource()
	{
		$url = new Catapult\URIResource("http://google.com/");

		$this->assertEquals((string)$url, "http://google.com/");
	}

	public function testEnsureResource()
	{
		$reference = array(); // must be passed by reference
		$data = Catapult\Ensure::Input($reference);

		$this->assertTrue($data instanceof Catapult\DataPacket);
	}

}

?>
