<?php

require_once('../source/Catapult.php');
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

try { 
    // we can either use our own
    // media, uploaded to Catapult
    // or any media url
    // parameter can be either an anchor '@'


    // first do a media file from catapult
    // we will try to find the last one
    // uploaded

    $text1 = "Catapult Media example using your Catapult library";
    $text2 = "Catapult Media example using absolute url";
    $text3 = "Catapult Media example using anchors";

    $media = new Catapult\Media;
    $last = $media->listMedia()->last();

    $message1 = new Catapult\Message(array(
        "from" => $argv[1],
        "to" => $argv[1],
        "text" => $text1,
        "media" => $last->content
    ));  


    // try an media text from any domain.
    // note: this will not make the file accessible
    // later by Catapult. Will only be used once

    $message2 = new Catapult\Message(array(
       "from" => $argv[1],
       "to" => $argv[1],
       "text" => $text2,
       "media" => "http://upload.wikimedia.org/wikipedia/commons/c/c1/PHP_Logo.png"
    ));


    printf("\nWe've MMSd this number, with your media, and ours!\n", $argv[2], $argv[3]);

} catch (CatapultApiException $e) {
    echo var_dump($e);
}
?>
