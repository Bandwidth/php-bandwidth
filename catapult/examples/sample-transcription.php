<?php
require_once("../source/Catapult.php");
// below is a sample of Catapult Transcriptions
// it will execute the provided verb 
// IMPORTANT: edit credentials.json
// with your information
// or comment out below /w your keys
//
// This example uses 0.7.0
// please note Credentials object is not here
// and does not need to be used anymore

$client = new Catapult\Client('BANDWIDTH_USER_ID', 'BANDWIDTH_API_TOKEN', 'BANDWIDTH_API_SECRET');
// uncomment if you are using 
// credentials.json
//$client = new Catapult\Client;
$recordings = new Catapult\Recording;
$recording = $recordings->listAll()->last();
$transcription = new Catapult\Transcription(array(
    "recordingId" => $recording->id,
    "text" => "This is a test..",
    "textUrl" => "any_friendly_url"
)); 

printf("You created a transcription for recording %s, titled: '%s'!", $recording->id, $transcription->text);
?>
