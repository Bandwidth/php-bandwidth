<?php
require_once("../source/Catapult.php");
// below is an extended example of using Domains and Endpoints
// it will create one Domain afterwards associate 
// endpoints to it using EndpointsMulti
//
// This example uses 0.7.0
// please note Credentials object is not herew
// and odes not need to be used anymore
$client = new Catapult\Client('BANDWIDTH_USER_ID', 'BANDWIDTH_API_TOKEN', 'BANDWIDTH_API_SECRET');
// comment out if using credentials.json
//$client = new Catapult\Client;

if (!isset($argv[1]) || !isset($argv[2])) {
  printf("Please provide the command line arguments like:%s php %s 'domain_name' 'description' 'endpoint_name'", PHP_EOL, $_SERVER['PHP_SELF']);
  exit(1);
}

$domain = new Catapult\Domains(array(
  "name" => $argv[1],
  "description" => "A description to match"
));

// we need to initialize this with
// our newly made domain id
$endpointsMulti = new Catapult\EndpointsMulti($domain->id);

// now we can assign as many endpoints
// as we require. This example will just iterate
// a sequence
for ($i = 0; $i != 10; $i ++) { // generate 10
   // pushEndpoint takes an array which
   // is similar to what the Endpoints model takes
   printf("Adding new endpoints on: %d%s", $i, PHP_EOL);
   $endpointsMulti->pushEndpoint(array(
    "name" => "endpoint-user-" .$i,
    "description" => "A description to match my newly made endpoint. this is the ${i}th.",
    "credentials" => array(
      "username" => "endpoint-user-${i}",
      "password" => "aPassword${i}",
      // generate a realm
      // for this user should
      // we not provide one
      // it will be generated for us
      //"realm" => "endpoint-test.bwapp.bwsip.io"
     ), 
     // genrate the sipUri
     // which is the equivalent of
     //
     // sip:domain_name@realm-name.bwapp.bwsip.io
     //
     // Providing this is also optional
     // it is for keeping things unique
     //

     //"sipUri" => "endpoint-user-${i}@endpoint-test.bwapp.bwsip.io" 
   ));
}

// now that we have set the endpoints
// we need to call execute
printf("Creating the endpoints.. this can take some time..%s", PHP_EOL);
$endpointsMulti->execute();

printf("All done.. You have created the endpoints and can use them.");
?>
