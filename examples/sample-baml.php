<?php
require_once('../source/Catapult.php');

// below is a sample of Catapult BaML
// it will execute the provided verb 
// IMPORTANT: edit credentials.json
// with your information
// or comment out below /w your keys
//
//$cred = new Catapult\Credentials('BANDWIDTH_USER_ID', 'BANDWIDTH_API_TOKEN', 'BANDWIDTH_API_SECRET');
$cred = new Catapult\Credentials;
// dont forget to comment out the implicit version if using assoc array

// this example is cli based
// use like:
// php ./sample-baml.php "verb" "attribute" "value"
// example
// php ./sample-baml.php "SpeakSentence" "voice" "female"

$client = new Catapult\Client($cred);

if (!(isset($argv[1]) || isset($argv[2]) || isset($argv[3])))
	die ("\nPlease provide command line input like: \n php ./sample-verb.php 'verb' 'attribute' 'value'\n\n");

try {

    $verb = $argv[1];
    $attribute = $argv[2];
    $value = $argv[3];


    $baml = new Catapult\BaML;
    $bverb = Catapult\BaMLVerb::fromString($verb);

    $bverb->create(array(
        new Catapult\BaMLAttribute($attribute, $value)
    ));

    // here we can add other things
    // verbs, attributes or text
    $bverb->addText("example");

    $baml->set($bverb);
    printf("The following BaML was generated: \n\n%s\n\n",$baml);


} catch (\CatapultApiException $e) {
	echo var_dump($e);	
}
?>
