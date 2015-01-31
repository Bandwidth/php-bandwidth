<?php
require_once('../source/Catapult.php');

// below is a sample getting all your 
// registered phone numbers, using both
// PhoneNumbers and NumberInfo service 
//
// IMPORTANT: edit credentials.json
// with your information
// or comment out below /w your keys
//
$cred = new Catapult\Credentials('u-mmuxnl7o2u2ijsdg2hrwdsq', 't-zer7uzfxvsvcbmlkqx6zgsq', 'o5ky4rsoacdd2oqvzl4cy6j3uqv6zpgjs3izbba');


// dont forget to comment out the implicit version if using assoc array

// this example is cli based
// use like:
// php ./sample-message.php "+from" "+to" "message"

$client = new Catapult\Client($cred);

try {
	$number_service = new Catapult\PhoneNumbers;
    $numbers = $number_service->listNumbers();	

	/** get the info for each number listed under this account **/

	printf("You have listed the following numbers under your account \n");

	foreach ($numbers->get() as $number) {
		printf("You have registered number: %d, on %s\n", $number->number, $number->createdTime);

		/** get even more information on this number using numberService **/

		$info = new Catapult\NumberInfo;
		//$info->get($number->number);


		//printf("Number area: %s\n", $info->name);	
	}

} catch (\CatapultApiException $e) {
	echo var_dump($e);	
}
?>
