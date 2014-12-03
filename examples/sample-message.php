<?php
require_once('../source/Catapult.php');

// below is a sample text message
// using Catapult's SMS feature
// IMPORTANT: edit credentials.json
// with your information
// or comment out below /w your keys
//
$cred = new Catapult\Credentials('USER_ID', 'API_TOKEN', 'API_SECRET');
//$cred = new Catapult\Credentials;
// dont forget to comment out the implicit version if using assoc array

// this example is cli based
// use like:
// php ./sample-message.php "+from" "+to" "message"

$client = new Catapult\Client($cred);


try {
	$message = new Catapult\Message(array(
		"from" => $argv[1],
		"to" => $argv[2], 
		"text" => $argv[3]
	));

	printf("We've messaged number: %s, said, %s!", $argv[2], $argv[3]);

} catch (\CatapultApiException $e) {
	echo var_dump($e);	
}
?>
