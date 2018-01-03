<?php
/* Unit tests for logs. These should
 * test the following functions
 * Comparatively to the credential unit tests
 * these run with no try/catching
 *
 *
 *
 * commands tested:
 * testWrite
 * testOn
 * testOff
 */

$cred = new Catapult\Credentials;
$client = new Catapult\Client($cred);


class LogTest extends PHPUnit_Framework_TestCase {
	/**
	 * test write should
	 * properly create a file
	 * write to it.
	 */
	public function testWrite()
	{
		Catapult\Log::write(time(), "ERROR_CODE", "TEST");
	}

	/**
	 * check if the log
	 * is on. usually on by default
	 */	
	public function testOn()
	{
		$this->assertTrue(Catapult\Log::isOn());
	}

	/**
	 * test for turning
	 * off logging support
	 */
	public function testOff()
	{
		Catapult\Log::on(FALSE);
		$this->assertTrue(!Catapult\Log::isOn());
	}

	/** 
	 * close the file
	 * handler handler
	 */
	public function testClose()
	{
		Catapult\Log::close();	
	}
}
?>
