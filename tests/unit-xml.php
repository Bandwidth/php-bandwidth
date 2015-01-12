<?php
/* Unit tests for Catapult Built in XML objects. 
 * These should
 * test the following functions
 * 
 * functions to test:
 * XML attribute parsing
 * XML attribute creation
 * XML attribute 
 * XML tree generation
 * XML tree join
 */

$cred = new Catapult\Credentials;
$client = new Catapult\Client($cred);

class XMLTest extends PHPUnit_Framework_TestCase {
    public function testGetAttributes() {

        $expected = 'test1="value1" test2="value2" test3="value3"';
        $attrs = array(
            new Catapult\BaMLAttribute("test1", "value1"),
            new Catapult\BaMLAttribute("test2", "value2"),
            new Catapult\BaMLAttribute("test3", "value3"),
        );

        /** make sure no padding is there for test **/
        $attrs_string = Catapult\XMLUtility::getAttributesCollection($attrs, 0);

        $this->assertEquals($attrs_string, $expected);
    }

    public function testFormTree() {
       $header = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>";
       $expected = $header . "<SpeakSentence><Record></Record></SpeakSentence>"; 
       $xml_struct = array(
              array(
                   "tag" => "SpeakSentence",
                   "level" => 1,
                   "type" => "open"
              ),
              array(
                   "tag" => "Record",
                   "level" => 2,
                   "type" => "complete"
              ),
              array(
                   "tag" => "SpeakSentence",
                   "level" => 1,
                   "type" => "close"
              )
        ); 

        /** no exception here. we're good **/
        $xml = Catapult\XMLUtility::MakePred($xml_struct);
        
    }

    public function testJoinTree() {
        $header = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>";
        $expected = "<Record></Record><SpeakSentence></SpeakSentence>";
        $verb1 = new Catapult\BaMLVerbRecord;
        $verb2 = new Catapult\BaMLVerbSpeakSentence;
        $verbs = array($verb1, $verb2);

        $str = Catapult\XMLUtility::joinTree($verbs);

        $this->assertEquals($str, $expected);
    }

}
