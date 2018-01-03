<?php
require_once("../source/Catapult.php");
// below is a sample of Catapult of joining
// multiple BaML verbs. 
//
// This example uses 0.7.0
// please note Credentials object is not here
// and does not need to be used anymore

$client = new Catapult\Client;
$baml = new Catapult\BaML;
$gatherVerb = new Catapult\BaMLGather;
$speakSentenceVerb = new Catapult\BaMLSpeakSentence;
$gatherVerb->addAttribute("terminatingDigits", "#");
$gatherVerb->addAttribute("maxDigits", "10");

$speakSentenceVerb->addText("Hello this is just an example.");

// this will nest one verb in another
$gatherVerb->addVerb($speakSentenceVerb);

// we can also directly edit the nested
// verb using gatherVerb!
$playAudio = new Catapult\BaMLPlayAudio;
$gatherVerb->addNestedVerb(0,$playAudio);

// remember to add the verb in our root container
// verbs need one root verb. 
$baml->set($gatherVerb);

// lets dump our container
echo var_dump($baml);

?>
