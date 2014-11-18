<?php
require_once('../source/Catapult.php');

// below is a sample conference 
// using Catapult's conference feature



$conference = new Catapult\Conference(array(
	"from" => $_argv[1],
));

$call = new Catapult\Call;

$call->create(array(
	"from" => $_argv[1],
	"to" => $_argv[2],
	"conferenceId" => $conference->id
));

$call->wait();

$call->speakSentence(array(
	"sentence" => "Hello. This is a sample conference call."
));

?>
