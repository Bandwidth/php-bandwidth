<?php

/* Unit tests for user errors. These should
 * test the following functions
 * Comparatively to the credential unit tests
 * these run with no try/catching
 * !IMPORTANT: calls unit test contants
 * one bridge test. Merge here in future
 *
 * commands tested:
 * listErrors/1
 * get/1
 * patch/1
 */

$cred = new Catapult\Credentials;
$client = new Catapult\Client($cred);


class UserErrorTest extends PHPUnit_Framework_TestCase {
	public function testUserErrorList()
	{
		$ue = new Catapult\UserError;

		$list = $ue->listErrors();

		$this->assertTrue(sizeof($list->get()) > 0);
	}	

	public function testUserErrorGet()
	{
		$ue = new Catapult\UserError;

		$ue->get("ue-id");
	}
}
?>
