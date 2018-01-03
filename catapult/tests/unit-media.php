<?php

/* Unit tests for media. These should
 * test the following functions
 * Comparatively to the credential unit tests
 * these run with no try/catching
 * this test DEPENDS on Catapult\Recordings
 *
 *
 *
 * commands tested:
 * upload/1
 * store/1
 */

$cred = new Catapult\Credentials;
$client = new Catapult\Client($cred);


class MediaTest extends PHPUnit_Framework_TestCase {
	public function testUpload()
	{
		$m = new Catapult\Media;

		$m->upload(array(
			"fileName" => __MEDIA_UNIT_TEST__,
			"file" => __MEDIA_UNIT_TEST_FILE_LOCATION__
		));

	}

	public function testStore()
	{
		$r = new Catapult\Recording;
		$recs = $r->listRecordings();

		$media = $recs->last()->getMediaFile();

		$media->store("filename.wav");
	}

	public function testListMedia()
	{
		$media = new Catapult\Media;

		$listings = $media->listMedia();

		$this->assertTrue(sizeof($listings) > 0);
	}

}

?>
