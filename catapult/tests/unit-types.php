<?php

/**
 * Unit tests for Catapult\Types 
 * 
 * classes tested
 * PhoneNumber
 * SIP
 * Callback
 * MediaUrl
 * DTMF
 * TextMessage
 * Timeout
 * Page
 * Size
 * Tag
 * Voice
 * Sentence
 * CallCombo
 * PhoneCombo
 */

$cred = new Catapult\Credentials;
$client = new Catapult\Client($cred);


class TypesTest extends PHPUnit_Framework_TestCase {
	public function testPhoneNumber()
	{
		$phone = new Catapult\PhoneNumber("+123405204");

		$this->assertEquals((string) $phone, "+123405204");
	}

	public function testSIP()
	{
		$sip = new Catapult\SIP("some@email.com");

		$this->assertEquals((string) $sip, "sip:some@email.com");
	}

	public function testCallback()
	{
		$url = new Catapult\Callback("http://google.com/");

		$this->assertEquals((string) $url, "http://google.com/");
	}

    public function testMediaURL()
    {
        $media = new Catapult\MediaUrl(__DEFAULT_MEDIA_URL__);

        $this->assertEquals((string) $media, __DEFAULT_MEDIA_URL__);
    }

	public function testDTMF()
	{
		$dtmf = new Catapult\DTMF("#123");

		$this->assertEquals((string) $dtmf, rawurlencode("#123"));
	}


	public function testTextMessage()
	{
		$text = new Catapult\TextMessage("unit test...");

		$this->assertEquals((string) $text, "unit test...");
	}

	public function testTimeout()
	{
		$timeout = new Catapult\Timeout(5);

		// in milliseconds through api

		$this->assertEquals((string) $timeout, "5000");
	} 

	public function testPage()
	{
		$page = new Catapult\Page(0);

		$this->assertEquals((string) $page, "0");
	}

	public function testSize()
	{
		$size = new Catapult\Size(1000);

		$this->assertEquals((string) $size, "1000");
	}

	public function testTag()
	{
		$tag = new Catapult\Tag("media tag");

		$this->assertEquals((string) $tag, "media tag");
	}

	public function testVoice()
	{
		$voice = new Catapult\Voice("Jorge");

		$this->assertEquals($voice->gender, "male");
		$this->assertEquals((string) $voice, "Jorge");
	}

	public function testSentence()
	{
		$sentence = new Catapult\Sentence("testing..");

		$this->assertEquals((string) $sentence, "testing..");
	}

	public function testCallCombo()
	{
		$combo = Catapult\CallCombo::Make(array(1, 2));

		$this->assertEquals(sizeof($combo), 1);
	}

	public function testPhoneCombo()
	{
		$combo = Catapult\PhoneCombo::Make(new Catapult\PhoneNumber("+122242"), new Catapult\PhoneNumber("+323232"));

		$this->assertEquals($combo['from'], "+122242");
		$this->assertEquals($combo['to'], "+323232");
	}

}

?>
