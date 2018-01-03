<?php
require_once("../source/Catapult.php");
// below is an example of Domains and Endpoints
// 
// This example uses 0.7.0 
// please note Credentials object is not here
// and does not need to be used anymore
$client = new Catapult\Client('BANDWIDTH_USER_ID', 'BANDWIDTH_API_TOKEN', 'BANDWIDTH_API_SECRET');
// comment if using credentials.json
//$client = new Catapult\Client;

// first we create a domain
// by using the Domains object
//
// Note:
// Domain names can be no more than
// 16 characters
//
$domain = new Catapult\Domains(array(
  "name" => "a-unique-domain",
  "description" => "A description to match"
));

// Creating an endpoint
//
// now we can create an endpoints
// for this we will need the following
// * required
//
// information:
// domainId*
// name*
// description
// credentials* 
// sipUri
$endpoint = new Catapult\Endpoints($domain->id, array(
  "name" => "an-endpoint",
  "description" => "a description fore the endpoint",
  "credentials" => array(
    "username" => "an-endpoint",
    "password" => "somethingStrong",
    // make a realm, when one
    // is not provided it is generated
    // for us
    "realm" => "a-test.bwapp.bwsip.io"
  ),
  // optional we can provide an sipUri
  // as well
  //
  // default format:
  // endpoint-name@realm.app.resource_provider.tld
  // 
  "sipUri" => "sip://an-endpointt@a-test.bwapp.bwsip.io"
));
?>
