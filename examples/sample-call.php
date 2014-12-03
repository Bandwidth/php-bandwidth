<?php
require_once('../source/Catapult.php');

// below is a sample call
// using Catapult's call feature
// IMPORTANT: edit credentials.json
// with your information
// or comment out below /w your keys
//
$cred = new Catapult\Credentials('USER_ID', 'API_TOKEN', 'API_SECRET');
//$cred = new Catapult\Credentials;
// dont forget to comment out the implicit version if using assoc array

$client = new Catapult\Client($cred);


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
