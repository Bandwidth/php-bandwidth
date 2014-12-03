<?php
/* on success runs Bandwidth/Catapult with Composer */
require_once("vendor/autoload.php");

try {
	$e = new Catapult\PhoneNumber("+1020232");

	echo "It worked -- Bandwidth/Catapult can now be loaded with composer..";
} catch (Exception $e) {
	echo "It does not work. Please make sure coposer is installed and you've ran composer install bandwidth/catapult";
}


?>
