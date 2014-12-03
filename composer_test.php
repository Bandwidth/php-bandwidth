<?php
/* on success runs Bandwidth/Catapult with Composer */
require_once("vendor/autoload.php");

try {

	$phoneNumber = new Catapult\PhoneNumber("+20202202");
	
	echo "It worked -- Bandwidth/Catapult can now be loaded with composer..";
} catch (Exception $e) {
	echo var_dump($e);
	echo "It does not work. Please make sure coposer is installed and you've ran composer install bandwidth/catapult";
}


?>
