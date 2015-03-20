<?php

require_once("./source/Catapult.php");

$client = new Catapult\Client;

$params = new Catapult\Parameters;
$params->setCredentials(array(
  "t" => 1
));

$domain = new Catapult\Domains(array(
  "name" => "test-" .rand(0, 100),
  "description" => "test"
));
$endpoint = new Catapult\Endpoints;
$params = new Catapult\Parameters;
$params->setDomainId($domain->id);
$params->setName("a-test-2");
$params->setDescription("a testing");
$params->setCredentials(array(
  "username"=>"someone@gmail.com",
  "password" => "aPassword",
  "realm" => "arealm"
));

$endpoint->create($params);
echo var_dump($endpoint);
$endpoint = new Catapult\Endpoints($domain->id, $endpoint->id);
echo var_dump($endpoint);
$domains = new Catapult\DomainsCollection;
foreach($domains->listAll(array("size" => 1000))->get() as $d) {

  $es = $d->listEndpoints();

  foreach($es->get() as $e) {
    $e->delete();
  }
  $d->delete();

}

