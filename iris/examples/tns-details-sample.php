<?php

require_once "./vendor/autoload.php";
require_once "./config.php";


if(count($argv) < 2) {
    die("usage: php tns-details-sample.php [number] e.g. php tns-details-sample.php 9195551212");
}

$tnarg = $argv[1];

$client = new Iris\Client(Config::LOGIN, Config::PASSWORD);

$tns = new Iris\Tns(null, $client);

$tn = $tns->tn($tnarg);
$tn->tndetails();

echo json_encode($tn->to_array());
