<?php
require_once('../source/Catapult.php');

// below is a sample call
// using Catapult's call feature

$call = new Catapult\Call(array(
	"from" => "__NUMBER__",
	"to" => "__TO__"
));

$call->wait();
?>
