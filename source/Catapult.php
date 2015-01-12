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
/** 
 * set timezone to Catapult's default 
 * this may or not be needed, depending on local setups. 
 * Once all the files are loaded, reset.
 */
date_default_timezone_set('UTC');

$phpver = explode('.', phpversion());

if (!($phpver[0] == '5' && $phpver[1] >= 3)) {
	// PHP not above or equal 5.3.0
	// We need this for namespaces
	// todo: implement '\' namespaces as '_' for
	// legacy versions
	Throw new \Exception("Catapult API supports PHP >= 5.3.0");
}

if (!(function_exists('curl_version'))) {
	//no curl support
	Throw new \Exception("Catapult needs libCURL..");
}

if (!(function_exists('xml_parse'))) {
    Throw new \Exception("Catapult BaML needs PHP's XML parser..");
}

$files = array("constants", "utils", "client", "states", "log", "exception", "collections", "resource", "generic", "types", "model", "credential", "client", "event", "baml");
foreach ($files as $f)
	require_once(realpath(__DIR__ . "/$f.php"));


?>
