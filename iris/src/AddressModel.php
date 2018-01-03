<?php

namespace Iris;

class Address {
    use BaseModel;

    protected $fields = array(
        "AddressType" => array(
            "type" => "string",
            "required" => true,
            "validate" => array(
                "type" => "in_array",
                "value" => array(
                    "Service",
                    "Billing",
                    "Dlda"
                )
            )
        ),
        "City" => array("type" => "string", "required" => true),
        "HouseNumber" => array("type" => "string", "required" => true),
        "StreetName" => array("type" => "string", "required" => true),
        "StateCode" => array("type" => "string", "required" => true)
    );

    public function __construct($data) {
        $this->set_data($data);
    }
}
