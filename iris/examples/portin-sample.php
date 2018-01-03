<?php

require_once "./vendor/autoload.php";
require_once "./config.php";


if(count($argv) < 2) {
    die("usage: php portin-sample.php [tn] e.g. php portin-sample.php 8183386247");
}

if(empty(Config::SITE) || empty(Config::SIPPEER)){
  die("You must configure a site and sip peer for this demo in your config file");
}

$client = new Iris\Client(Config::LOGIN, Config::PASSWORD);
$account = new Iris\Account(Config::ACCOUNT, $client);

$number = $argv[1];

$res = $account->lnpChecker([ $number ], "true");

if($res->PortableNumbers->Tn == $number) {
    echo "Your number is portable. Creating PortIn Order";

    $portin = $account->portins()->create(array(
        "BillingTelephoneNumber" => $number,
        "Subscriber" => array(
            "SubscriberType" => "BUSINESS",
            "BusinessName" => "Acme Corporation",
            "ServiceAddress" => array(
                "HouseNumber" => "1623",
                "StreetName" => "Brockton Ave",
                "City" => "Los Angeles",
                "StateCode" => "CA",
                "Zip" => "90025",
                "Country" => "USA"
            )
        ),
        "LoaAuthorizingPerson" => "John Doe",
        "ListOfPhoneNumbers" => array(
            "PhoneNumber" => [ $number ]
        ),
        "SiteId" => CONFIG::SITE,
        "Triggered" => "false"
    ));

    $filename = $portin->loas_send(__DIR__."/loa.pdf", array("Content-Type" => "application/pdf"));

    echo "\nSuccessfully uploaded LOA: ";
    echo $filename;

    $portin->loas_update(__DIR__."/loa.pdf", $filename, array("Content-Type" => "application/pdf"));

    echo "Successfully updated";
}
