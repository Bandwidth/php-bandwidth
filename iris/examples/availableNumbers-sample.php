<?php

require_once "./vendor/autoload.php";
require_once "./config.php";


if(count($argv) < 3) {
    die("usage: php availableNumbers-sample.php [state] [qty] e.g. php availableNumbers-sample.php CA 3");
}

$client = new Iris\Client(Config::LOGIN, Config::PASSWORD);
$account = new Iris\Account(Config::ACCOUNT, $client);
echo json_encode($account->availableNumbers(["state" => $argv[1], "quantity" => $argv[2]]));
