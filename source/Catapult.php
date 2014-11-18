<?php

/* In place of Composer and 
 * autoloader.
 * real version would be composer based
 *
 * define namespace here
 * so we can extend later
 */
namespace Catapult;

$files = array("constants", "utils", "states", "exception", "collections", "resource", "generic", "types", "model", "credential", "client", "event");
foreach ($files as $f)
	require_once(realpath(__DIR__ . "/$f.php"));


?>
