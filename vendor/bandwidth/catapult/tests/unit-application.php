<?php

/* Unit tests for applications. These should
 * test the following functions
 * Comparatively to the credential unit tests
 * these run with no try/catching
 * !IMPORTANT: calls unit test contants
 * one bridge test. Merge here in future
 *
 * commands tested:
 * get/1
 * create/1
 * patch/1
 */

$cred = new Catapult\Credentials;
$client = new Catapult\Client($cred);


class ApplicationsTest extends PHPUnit_Framework_TestCase {
	public function testApplicationCreate()
	{
		$params = new Catapult\Parameters;
		$app = new Catapult\Application;

		$params->setName(__APPLICATION_UNIT_TEST__ . time());

		$app->create($params);

		$this->assertTrue($app->id);
	}	

	public function testListApplication()
	{
		$app = new Catapult\Application;

		$apps = $app->listApplications();


		$this->assertTrue(sizeof($apps->get()) > 0); // should be more than one by now
	}

	public function testPatchApplication()
	{
		$app = new Catapult\Application;

		$app->get("app-id");

		$app->patch(array(
			"incomingCallUrl" => "NEW_URL"
		));

		$this->assertEquals($app->incomingCallUrl, "NEW_URL");	
	}
}
?>
