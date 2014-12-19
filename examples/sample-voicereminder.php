<?php
require_once('../source/Catapult.php');

// below is a voice reminder 
// example. 
// More here:
// https://catapult.inetwork.com/docs/guides/making-call/
//
// IMPORTANT: edit credentials.json
// with your information
// or comment out below /w your keys
$cred = new Catapult\Credentials('BANDWIDTH_USER_ID', 'BANDWIDTH_API_TOKEN', 'BANDWIDTH_API_SECRET');
//$cred = new Catapult\Credentials;
// dont forget to comment out the implicit version if using assoc array

if (!(isset($argv[1]) || isset($argv[2])))
	die ("\nPlease provide command line input like: \n php ./sample-call.php 'from' 'to'\n\n");

try {


	/** for this example we will use Catapult events in place of call->wait **/

	$call = new Catapult\Call(array(
		"from" => new Catapult\PhoneNumber($argv[1]),
		"to" => new Catapult\PhoneNumber($argv[2]),
	));	

	
	$call->event_wait();

	$gather = new Catapult\Gather($call->id);	

	


} catch (\CatapultApiException $e) {

}
?>
