<?php

namespace Iris;

class ServiceAddress {
    use BaseModel;

    protected $fields = array(
        "City" => array("type" => "string", "required" => true),
        "HouseNumber" => array("type" => "string", "required" => true),
        "StreetName" => array("type" => "string", "required" => true),
        "StateCode" => array("type" => "string", "required" => true),
        "Zip" => array("type" => "string"),
        "Country" => array("type" => "string"),
        "County" =>  array("type" => "string"),
        "HousePrefix" => array("type" => "string"),
        "HouseSuffix" => array("type" => "string"),
        "PreDirectional" => array("type" => "string"),
        "StreetSuffix" => array("type" => "string"),
        "PostDirectional" => array("type" => "string"),
        "AddressLine2" => array("type" => "string"),
        "PlusFour" => array("type" => "string"),
        "AddressType" => array("type" => "string"),
    );

    public function __construct($data) {
        $this->set_data($data);
    }
}
