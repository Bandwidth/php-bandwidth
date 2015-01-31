<?php

/* Unit tests for transscriptions. These should
 * test the following functions
 * Comparatively to the credential unit tests
 * these run with no try/catching.
 * 
 * Transcription tests depend on a recording.
 * we use the last recording on the used account
 * if not available please ensure it is.
 *
 * --------------------------------------------------------------------------
 *
 * v0.5.0 supported transcription as collections
 * starting from v0.7.0, if you plan on using these as singular objects,
 * you will only need to read
 * get/1
 * create/1
 *
 * commands tested:
 * listTranscriptions/1 
 * get/1
 * create/1
 * get/1 (unit-media.php for more)
 */

$cred = new Catapult\Credentials;
$client = new Catapult\Client($cred);


class TranscriptionsTest extends PHPUnit_Framework_TestCase {
	public function testGetTranscription()
	{
        $recording = new Catapult\Recording;
        $last = $recording->listRecordings()->last();
        
		$trans = new Catapult\Transcription($last->id);


		$this->assertTrue($trans->id);
	}

	public function testCreateTranscription()
	{
        $recording = new Catapult\Recording;
        $last = $recording->listRecordings()->last();

        $trans = new Catapult\Transcription($last->id);

        $trans->create(array(
            "chargeableDuration" => 60,
            "text" => "Test transcription",
            "textUrl" => "test",
        ));


		$this->assertEquals($trans->chargeableDuration, 60);
		$this->assertEquals($trans->text, "test transcription");
		$this->assertEquals($trans->textUrl, "test");
	}

    /**
     * test guarentees atleast one
     * transcription.
     *
     * also check if the count has been incremented
     */
	public function testListTranscriptions()
	{
        $recording = new Catapult\Recording;
        $last = $recording->listRecordings()->last();
        $trans = new Catapult\Transcription($last->id);

        $transcriptions = $trans->listTranscriptions();

        $this->assertTrue(sizeof($transcriptions->data) > 0);
	}

}

?>
