<?php

require_once "./vendor/autoload.php";
require_once "./config.php";


if(count($argv) < 2) {
    die("usage: php order-create-sample.php [tn] e.g. php order-create-sample.php 9193752369");
}

$client = new Iris\Client(Config::LOGIN, Config::PASSWORD);

$account = new Iris\Account(Config::ACCOUNT, $client);
$order = $account->orders()->create([
    "Name" => "Available Telephone Number order",
    "SiteId" => "2297",
    "CustomerOrderId" => "123456789",
    "ExistingTelephoneNumberOrderType" => [
        "TelephoneNumberList" => [
            "TelephoneNumber" => [ $argv[1] ]
        ]
    ]
]);

echo json_encode($order->to_array());
