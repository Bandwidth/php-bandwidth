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

require_once("Autoload.php");
error_reporting(E_ALL);
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
    Throw new \Exception("Catapult API supports PHP >= 5.3.0");
}
if (!(function_exists('curl_version'))) {
    //no curl support
    Throw new \Exception("Catapult needs libCURL..");
}
if (!(function_exists('xml_parse'))) {
    Throw new \Exception("Catapult BaML needs PHP's XML parser..");
}
if (!(function_exists('json_encode'))) {
    Throw new \Exception("Catapult's RESTClient uses JSON, you need to enable json in PHP!");
}

/** v0.7.0 use directories **/
$dirs = array("utils", "core", "resource", "models", "baml", "events", "types");
foreach ($dirs as $d) {
    includeDir(realpath(__DIR__ . "/$d"));
}
