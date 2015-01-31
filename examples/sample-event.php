<?php
require_once('../source/Catapult.php');

// below is a sample event 
// this will listen for a call event
// and when received output to screen
// IMPORTANT: edit credentials.json
// with your information
// or comment out below /w your keys

// For events, you will need to make sure
// the callback url is set to this destination.
// You can set this at:
//
// https://catapult.inetwork.com/pages/catapult.jsf
// (Callback HTTP URL)
//
$cred = new Catapult\Credentials('BANDWIDTH_USER_ID', 'BANDWIDTH_API_TOKEN', 'BANDWIDTH_API_SECRET');
//$cred = new Catapult\Credentials;
// dont forget to comment out the implicit version if using assoc array

$client = new Catapult\Client($cred);


try {

	$event = new Catapult\Event($_REQUEST);	

	/**
	 * Once we have received an event
	 * log the event, in logs (./logs)
         * Arrange event with time event was received:
         * event_{time}.log
	 */
	 printf("New event, id: %d", $event->id);
	 file_put_contents("./" . $event->time . ".log", json_encode($event));

} catch (\CatapultApiException $e) {
	echo var_dump($e);	
}
?>
