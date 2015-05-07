<?php
require_once('../source/Catapult.php');

// below is a sample call search 
// it will look through your calls and find
// the last incoming call from a number
// IMPORTANT: edit credentials.json
// with your information
// or comment out below /w your keys
//
$cred = new Catapult\Credentials;
// dont forget to comment out the implicit version if using assoc array

$client = new Catapult\Client($cred);

if (!(isset($argv[1]) || isset($argv[2])))
	die ("\nPlease provide command line input like: \n php ./sample-find-call.php 'from'\n\n");

try {
	$calls = new Catapult\CallCollection;
  $callIterator = $calls->listIterator(array("direction" => "in",  "from" => $argv[1]));
  $allCalls = $callIterator->fetchAll();
  foreach ($calls as $call) {
    if ($call->direction == "in" && $call->from == $argv[1]) {
       printf("We've found the last call from %s. It was at %s", $argv[1], $call->startTime);
       die;
    }
  }

printf("We couldnt find that call.");

} catch (\CatapultApiException $e) {
	echo var_dump($e);	
}
?>
