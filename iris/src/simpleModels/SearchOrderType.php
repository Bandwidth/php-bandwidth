<?php

namespace Iris;

class SearchOrderType {
    use BaseModel;

    protected $fields = array(
        "NpaNxx" => array("type" => "string"),
        "EnableTNDetail" => array("type" => "string"),
        "EnableLCA" => array("type" => "string"),
        "RateCenter" => array("type" => "string"),
        "State" => array("type" => "string"),
        "City" => array("type" => "string"),
        "AreaCode" => array("type" => "string"),
        "TollFreeVanity" => array("type" => "string"),
        "Quantity" => array("type" => "string"),
        "TollFreeWildCardPattern" => array("type" => "string"),
        "Zip" => array("type" => "string"),
        "Lata" => array("type" => "string"),
    );
    public function __construct($data) {
        $this->set_data($data);
    }
}
