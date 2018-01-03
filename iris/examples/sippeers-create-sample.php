<?php

require_once "./vendor/autoload.php";
require_once "./config.php";


if(count($argv) < 3) {
    die("usage: php sippeers-create-sample.php [ip] [sippeer name] e.g. php sippeers-create-sample.php 92.168.181.95 peer12");
}

if(empty(Config::SITE)){
  die("You must configure a site and sip peer for this demo in your config file");
}

$client = new Iris\Client(Config::LOGIN, Config::PASSWORD);
$account = new Iris\Account(Config::ACCOUNT, $client);

$host = $argv[1];
$name = $argv[2];

$sippeer = $account->sites()->site(Config::SITE)->sippeers()->create(array(
        "PeerName" => $name,
        "IsDefaultPeer" => true,
        "ShortMessagingProtocol" => "SMPP",
        "VoiceHosts" => array(
            "Host" => array(
                "HostName" => $host
            )
        ),
        "SmsHosts" => array(
            "Host" => array(
                "HostName" => $host
            )
        ),
        "TerminationHosts" => array(
            "TerminationHost" => array(
                "HostName" => $host,
                "Port" => 0,
                "CustomerTrafficAllowed" => "DOMESTIC",
                "DataAllowed" => true
            )
        )
));

echo json_encode($sippeer->to_array());
