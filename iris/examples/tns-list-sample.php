<?php

require_once "./vendor/autoload.php";
require_once "./config.php";


if(count($argv) < 2) {
    die("usage: php tns-list-sample.php [npa] e.g. php tns-list-sample.php 818");
}

$client = new Iris\Client(Config::LOGIN, Config::PASSWORD);

$tns = new Iris\Tns(null, $client);

$list = $tns->getList(["page" => 1, "size" => 10, "npa" => $argv[1] ]);

echo json_encode($list);
