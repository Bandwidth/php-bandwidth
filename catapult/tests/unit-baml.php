<?php
/* Unit tests for BaML. These should
 * test the following functions
 * 
 * functions to test:
 * BaML verb creation
 * BaML attribute creation
 * BaML stringify 
 * BaML parsing
 */

$cred = new Catapult\Credentials;
$client = new Catapult\Client($cred);

class BaMLTest extends PHPUnit_Framework_TestCase {
    /**
     * Test a verb creation
     */
    public function testVerbCreation() {
        $baml = new Catapult\BaML;
        $verb = new Catapult\BaMLVerbRecord;

        $baml->set($verb);


        /**
         * should be two slots
         * 1: container
         * 2: this verb
         */
        $this->assertEquals(sizeof($baml->data), 2);
    }
    
    public function testAttributes() {
        $baml = new Catapult\BaML;
        $verb = new Catapult\BaMLVerbRecord;

        $verb->addAttribute("requestUrl", __DEFAULT_URL__);
        $verb->addAttribute("maxDuration", 30);

        $baml->set($verb);

        $this->assertEquals(sizeof($verb->getAttributes()), 2);

    }

    public function testNestedVerb() {
        $baml = new Catapult\BaML;
        $verb = new Catapult\BaMLVerbGather;
        $verb1 = new Catapult\BaMLVerbSpeakSentence;

        $verb->addVerb($verb1);
        $baml->set($verb);

        $this->assertEquals(sizeof($verb->getVerbs()), 1);
    }

    public function testText() {
        $baml = new Catapult\BaML;
        $verb = new Catapult\BaMLVerbGather;
        $verb->addText("Gather text");

        $this->assertEquals($verb->getText(), "Gather text");
    }

    public function testStringify() {

        $to = "<?xml version=\"1.0\" encoding=\"UTF-8\"?><Response><Gather><SpeakSentence gender=\"female\" locale=\"en\" voice=\"female\"></SpeakSentence></Gather></Response>";

        $baml = new Catapult\BaML("Response");
        $verb = new Catapult\BaMLVerbGather;
        $verb1 = new Catapult\BaMLVerbSpeakSentence;
        $verb1->addAttribute("gender", "female");
        $verb1->addAttribute("locale", "en");
        $verb1->addAttribute("voice", "female");

        $verb->addVerb($verb1);
        $baml->set($verb); 

        $this->assertEquals((string) $baml, $to); 
    }

    public function testParsing() {
        $baml = new Catapult\BaML;
        $xml = "<?xml version=\"1.0\" encoding=\"UTF-8\"?><Response><Gather><SpeakSentence gender=\"female\" locale=\"en\" voice=\"female\">Hello this is test text</SpeakSentence></Gather></Response>";
        $baml->parse($xml);
        $this->assertEquals($xml, $baml);
    }


    public function testSendMessage() {
        $baml = new Catapult\BaML;
        $verb = new Catapult\BaMLSendMessage;
        $verb->addAttribute("from", "+12345");
        $verb->addAttribute("to", "+1234");


        $baml->set($verb);

        $this->assertEquals(sizeof($baml->getVerbs()), 1);
    }

    public function testRedirect() {
        $baml = new Catapult\BaML;
        $verb = new Catapult\BaMLRedirect;
        $verb->addAttribute("requestUrl", __DEFAULT_URL__);
        
        $baml->set($verb);


        $this->assertEquals(sizeof($baml->getVerbs()), 1);
    }

    public function testHangup() {
        $baml = new Catapult\BaML;
        $verb = new Catapult\BaMLHangup;
        $baml->set($verb);
    
        $this->assertEquals(sizeof($baml->getVerbs()), 1);
    }

    public function testTransfer() {
        $baml = new Catapult\BaML;
        $verb = new Catapult\BaMLTransfer;
        $verb->addAttribute("transferTo", __DEFAULT_RECEIVER__);
        $verb->addAttribute("transferCallerId", __DEFAULT_CALLER_ID__);
        $baml->set($verb);

        $this->assertEquals(sizeof($baml->getVerbs()), 1);
    }

    public function testPlayAudio() {
        $baml = new Catapult\BaML;
        $verb = new Catapult\BaMLPlayAudio;
        $verb->addAttribute("audioUrl", __MEDIA_UNIT_TEST_FILE__);
        $verb->addAttribute("digits", "1");

        $baml->set($verb);

        $this->assertEquals(sizeof($baml->getVerbs()), 1);
    }

    public function testGather() {
        $baml = new Catapult\BaML;
        $verb = new Catapult\BaMLGather;
        $verb->addAttribute("requestUrl", __DEFAULT_URL__);
        $verb->addAttribute("requestUrlTimeout", 5);
        $verb->addAttribute("maxDigits", 5);
        $verb->addAttribute("interDigitTimeout", 5);
        $verb->addAttribute("bargeable", 1);
        $verb->addAttribute("bargeable", 1);

        $baml->set($verb);

        $this->assertEquals(sizeof($baml->getVerbs()), 1);
    }

    public function testFromFile() {
        $baml = new Catapult\BaML;

        $baml->getAsStream(__BAML_UNIT_TEST_FILE_LOCATION__); 
    }


    public function testSelfCopy() {
        $baml = new Catapult\BaML;

        $verb = new Catapult\BaMLGather;

        /** this should throw a memory error **/
        $verb->addVerb($verb);

    }
    
}
