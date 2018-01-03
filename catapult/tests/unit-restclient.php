<?php
/* Unit tests for RESTClient. These should
 * test the following functions
 * Comparatively to the credential unit tests
 * these run with no try/catching
 *
 *
 *
 * commands tested:
 * SSLOn 
 * SSLOff 
 * SSL Private key 
 */


class RESTClientTest extends PHPUnit_Framework_TestCase {
	/**
	 * REST SSL key testing
	 */
	public function testSSLKey()
	{
		Catapult\RESTClient::sslKey(realpath("../keys/priv.pem"));
	}

	/**
         * Test Catapult with SSL off. Note
         * this should not work
	 */	
	public function testOff()
	{
		Catapult\RESTClient::ssl(FALSE);
	}

	/**
	 * Test with SSL on verify both peer and
         * host.
	 */
	public function testOn()
	{
		Catapult\RESTClient::ssl(TRUE);
	}

	/**
	 * turn verification
	 * off. requests should still
	 * work with ssl. just not verify
	 */
	public function testVerify()
	{
		Catapult\RESTClient::verify(FALSE);
	}

}
?>
