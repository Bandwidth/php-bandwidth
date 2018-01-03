<?php

require_once "./vendor/autoload.php";
require_once "./config.php";


if(count($argv) < 3) {
    die("usage: php NpaNxx-search-sample.php [areaCode] [qty] e.g. php NpaNxx-search-sample.php 949 3");
}

$client = new Iris\Client(Config::LOGIN, Config::PASSWORD);
$account = new Iris\Account(Config::ACCOUNT, $client);
echo json_encode($account->availableNpaNxx(["areaCode" => $argv[1], "quantity" => $argv[2]]));
