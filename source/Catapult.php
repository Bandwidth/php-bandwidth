<?php

/* In place of Composer and 
 * autoloader.
 * to include with composer
 * we need 
 * require "vendor/autoload.php"
 * conversly to include this
 * use:
 * require "source/Catapult.php"
 *
 * either should bootstrap all
 * needed files.
 *
 * define namespace here
 * so we can extend later
 */
namespace Catapult;

error_reporting(E_ALL ^ E_STRICT);

$files = array("constants", "utils", "client", "states", "log", "exception", "collections", "resource", "generic", "types", "model", "credential", "client", "event");
foreach ($files as $f)
	require_once(realpath(__DIR__ . "/$f.php"));


?>
