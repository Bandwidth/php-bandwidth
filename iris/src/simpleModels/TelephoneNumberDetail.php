<?php

namespace Iris;

class TelephoneNumberDetail {
    use BaseModel;

    protected $fields = array(
        "City" => array("type" => "string"),
        "LATA" => array("type" => "string"),
        "Lata" => array("type" => "string"),
        "RateCenter" => array("type" => "string"),
        "State" =>  array("type" => "string"),
        "FullNumber" =>array("type" => "string"),
        "Tier" =>array("type" => "string"),
        "VendorId" =>array("type" => "string"),
        "VendorName" =>array("type" => "string"),
        "Status" =>array("type" => "string"),
        "AccountId" =>array("type" => "string"),
        "LastModified" =>array("type" => "string"),
    );

    public function __construct($data) {
        $this->set_data($data, true);
    }
}
