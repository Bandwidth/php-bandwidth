<?php
require_once('../source/Catapult.php');

// below is a sample conference 
// using Catapult's conference feature

// IMPORTANT: edit credentials.json
// with your information
// or comment out below /w your keys
//
$cred = new Catapult\Credentials('USER_ID', 'API_TOKEN', 'API_SECRET');
//$cred = new Catapult\Credentials;
// dont forget to comment out the implicit version if using assoc array
$client = new Catapult\Client($cred);

try {
	$conference = new Catapult\Conference(array(
		"from" => $argv[1],
	));

	$call = new Catapult\Call;

	$call->create(array(
		"from" => new Catapult\PhoneNumber($argv[1]),
		"to" => new Catapult\PhoneNumber($argv[2]),
		"conferenceId" => $conference->id
	));

	$call->wait();

	$call->speakSentence(array(
		"sentence" => "Hello. This is a sample conference call."
	));
	
} catch (\CatapultApiException $e) {
	echo var_dump($e);
}

?>
