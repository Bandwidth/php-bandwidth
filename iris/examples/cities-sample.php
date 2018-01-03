<?php

require_once "./vendor/autoload.php";
require_once "./config.php";


if(count($argv) < 2) {
    die("usage: php cities-sample.php [state] e.g. php cities-sample.php CA");
}

$client = new Iris\Client(Config::LOGIN, Config::PASSWORD);

$citiesInstance = new \Iris\Cities($client);
echo json_encode($citiesInstance->getList(["state" => $argv[1]]));
