<?php
require_once('../source/Catapult.php');


// below is a sample phone call
// NOTE:
// versions 0.5.0 - 0.8.0 RC2 
// used a deprecated feature
// this is a rewrite

$cred = new Catapult\Client('BANDWIDTH_USER_ID', 'BANDWIDTH_API_TOKEN', 'BANDWIDTH_API_SECRET');
// comment out if you have
// credentials.json
//$cred = new Catapult\Client;
//
// this is a command line
// program and will function like:
//
// php ./sample-call '+FROMNUMBER' '+TONUMBER'
define("ARGS_NEEDED", 3);
define("ARGS_DESC", "./sample-call.php '+FROMNUMBER' '+TONUMBER'");

try {
  if (sizeof($argv)  == ARGS_NEEDED) { 
    $call = new Catapult\Call(array(
      "from" => $argv[1],
      "to" => $argv[2]
    ));
  } else {
    printf("You must supply at least %s arguments like: %s", ARGS_NEEDED, ARGS_DESC);
  }

} catch (CatapultApiException $exception) {
  $result = $exception->getResult();
  // do something with the result
}
