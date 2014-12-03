<?php
require_once('../source/Catapult.php');

// below is a sample gather test
// it will call the number, prompt for
// a gather and if it is valid
// show a message. if not repeat
// IMPORTANT: edit credentials.json
// with your information
// or comment out below /w your keys
//
$cred = new Catapult\Credentials('USER_ID', 'API_TOKEN', 'API_SECRET');
//$cred = new Catapult\Credentials;
// dont forget to comment out the implicit version if using assoc array

$client = new Catapult\Client($cred);


try {
	$call = new Catapult\Call;

	$call->create(array(
		"from" => $argv[1],
		"to" => $argv[2]
	));

	$call->wait();

	$gather = new Catapult\Gather($call->id);

	$gather->create(array(
		"maxDigits" => 10, 
		"terminatingDigits" => '#', 
		"prompt" => array('sentence' => 'Please enter your 5 digit code')
	));

	$call->speakSentence(array(
		"sentence" => "Please enter your digits."	
	));

	if ($gather->check("digits", $argv[3])) 
		$call->speakSentence(array(
			"sentence" => "Correct."		
		));
	else
		$call->speakSentence(array(
			"sentence" => "The digits were wrong"
		));
	

} catch (\CatapultApiException $e) {
	echo var_dump($e);	
}
?>
