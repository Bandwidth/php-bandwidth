<?php

/* Unit tests for recordings. These should
 * test the following functions
 * Comparatively to the credential unit tests
 * these run with no try/catching
 *
 *
 *
 * commands tested:
 * listRecordings/1 
 * get/1
 * getMediaFile/1 (unit-media.php for more)
 */

$cred = new Catapult\Credentials;
$client = new Catapult\Client($cred);


class RecordingTest extends PHPUnit_Framework_TestCase {
	public function testListRecordings()
	{
		$r = new Catapult\Recording;
		$recordings = $r->listRecordings();

		$this->assertTrue(sizeof($recordings) > 0);
	}

	public function testGet()
	{
		$r = new Catapult\Recording;
		$recs = $r->listRecordings();
		$d = $recs->get();


		$this->assertEquals($r->id, "r-id");
	}

	public function testGetMediaFile()
	{
		$r = new Catapult\Recording("r-id");

		$media = $r->get_media_file();

		$this->assertInstanceOf($media, Catapult\Media);
	}

}

?>
