<?php

require_once "./vendor/autoload.php";
require_once "./config.php";


if(count($argv) < 2) {
    die("usage: php order-get-sample.php [tn] e.g. php order-get-sample.php e2cbe50e-a23e-4bdc-b92b-00eff444ca17");
}

$client = new Iris\Client(Config::LOGIN, Config::PASSWORD);

$account = new Iris\Account(Config::ACCOUNT, $client);
$order = $account->orders()->order($argv[1]);

echo json_encode($order->to_array());
