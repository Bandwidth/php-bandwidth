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
define("ARGS_NEEDED", 4);
define("ARGS_DESC", "To use this example, supply arguments as follows: php ./sample-mms.php {my_media_title} {my_media_file} {from} {to}" . PHP_EOL);
try { 

    if (sizeof($argv) !== ARGS_NEEDED) {
      print(ARGS_DESC);

    } else {
      $text1 = "Catapult Media example using your Catapult library";

      $media = new Catapult\Media;
      $media->upload(array(
        "mediaName" => $argv[1],
        "file" => $argv[2]
      ));

      $message1 = new Catapult\Message(array(
          "from" => $argv[3],
          "to" => $argv[4],
          "text" => $text1,
          "media" => $media->url
      ));  

      printf("\nWe've MMSd this number, using newly created media: %s!\n", $argv[1]);
  }

} catch (CatapultApiException $e) {
    echo var_dump($e);
}
?>
