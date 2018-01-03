<?php

require_once "./vendor/autoload.php";
require_once "./config.php";


if(count($argv) < 2) {
    die("usage: php coveredRate-sample.php [state] e.g. php coveredRate-sample.php CA");
}

$client = new Iris\Client(Config::LOGIN, Config::PASSWORD);
$rc = new \Iris\RateCenter($client);

echo json_encode($rc->getList(["state" =>$argv[1]]));
