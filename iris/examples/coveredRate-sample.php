<?php

require_once "./vendor/autoload.php";
require_once "./config.php";


if(count($argv) < 2) {
    die("usage: php coveredRate-sample.php [zip] e.g. php coveredRate-sample.php 27609");
}

$client = new Iris\Client(Config::LOGIN, Config::PASSWORD);
$rcs = new Iris\CoveredRateCenters($client);
echo json_encode($rcs->getList(["zip" => $argv[1], "page" => "1", "size" => "30"]));
