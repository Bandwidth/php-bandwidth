<?php

/* Unit tests for client and RESTful client. These should
 * this is not a model based test and will not 
 * communicate with the actual API
 *
 * To run the client test, you need to make sure
 * the listed defaults will listen for 'GET', 'POST', 'PUT' and 'DELETE'
 * commands tested:
 * get
 * post
 * delete
 * put
 */

$cred = new Catapult\Credentials;
$client = new Catapult\Client($cred);


class ClientTest extends PHPUnit_Framework_TestCase {
	public function testGet()
	{
		$client = Catapult\Client::Get();

		$res = $client->get(__UNIT_TEST_GET_URL__);

		$this->assertTrue(is_object($res));
	}

	public function testPost()
	{
		$client = Catapult\Client::Get();

		$client->post(__UNIT_TEST_POST_URL__);

		$this->assertTrue(is_object($res));
	}
		
	public function testDelete()
	{
		$client = Catapult\Client::Get();

		$client->delete(__UNIT_TEST_DELETE_URL__);

		$this->assertTrue(is_object($res));
	}
	
	public function testPut()
	{
		$client = Catapult\Client::Get();

		$client->put(__UNIT_TEST_PUT_URL__);

		$this->assertTrue(is_object($res));
	}

}

?>
