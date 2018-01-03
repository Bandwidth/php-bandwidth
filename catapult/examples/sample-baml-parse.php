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

$client = new Catapult\Client($cred);

// generates objects from the BaML string
try {

    $baml = new Catapult\BaML;
    $baml->parse("
<Request>
    <SpeakSentence voice=\"male\" locale=\"en\">Hello Example</SpeakSentence>
    <Redirect requestUrl=\"http://example.org\">Redirect</Redirect>
</Request>
    ");

    printf("generated the following BaML objects\n");
    foreach ($baml->getVerbs() as $verb) {
        printf("Verb: %s was generated with attributes (%s) and text: (%s)\n", $verb->getName(), $verb->getAttributesString(), $verb->getText());
    }


} catch (\CatapultApiException $e) {
	echo var_dump($e);	
}
?>
