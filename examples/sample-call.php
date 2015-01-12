<?php
require_once('../source/Catapult.php');

// below is a sample call
// using Catapult's call feature
// IMPORTANT: edit credentials.json
// with your information
// or comment out below /w your keys
//

//$cred = new Catapult\Credentials('BANDWIDTH_USER_ID', 'BANDWIDTH_API_TOKEN', 'BANDWIDTH_API_SECRET');
$cred = new Catapult\Credentials;
// dont forget to comment out the implicit version if using assoc array

$client = new Catapult\Client($cred);

if (!(isset($argv[1]) || isset($argv[2])))
	die ("\nPlease provide command line input like: \n php ./sample-call.php 'from' 'to'\n\n");

try {
	$call = new Catapult\Call(array(
		"from" => new Catapult\PhoneNumber($argv[1]),
		"to" => new Catapult\PhoneNumber($argv[2])
	));

	$call->wait();

	$call->hangup();

	printf("We called your number: %s and closed the connection gracefully.", $argv[2]);

} catch (\CatapultApiException $e) {
	echo var_dump($e);	
}
?>
