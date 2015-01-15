<?php
require_once('../source/Catapult.php');

// below is a sample text message
// using Catapult's SMS feature
// IMPORTANT: edit credentials.json
// with your information
// or comment out below /w your keys
//

$cred = new Catapult\Credentials('BANDWIDTH_USER_ID', 'BANDWITH_API_TOKEN', 'BANDWIDTH_API_SECRET');
//$cred = new Catapult\Credentials;
// dont forget to comment out the implicit version if using assoc array

// this example is cli based
// use like:
// php ./sample-message.php "+from" "+to" "message"

$client = new Catapult\Client($cred);

if (!(isset($argv[1]) || isset($argv[2]) || isset($argv[3])))
    die ("\nPlease provide command line input like: \n php ./sample-message.php 'from' 'to' 'message'\n\n");

try {
    $message = new Catapult\Message(array(
        "from" => new Catapult\PhoneNumber($argv[1]),
        "to" => new Catapult\PhoneNumber($argv[2]), 
        "text" => new Catapult\TextMessage($argv[3])
    ));


    printf("\nWe've messaged number: %s, said, %s!\n", $argv[2], $argv[3]);

} catch (\CatapultApiException $e) {
    echo var_dump($e);  
}
?>
