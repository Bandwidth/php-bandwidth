<?php

namespace Iris;

class CitiesS {
    use BaseModel;

    protected $fields = array(
        "City" => array("type" => "string"),
        "RcAbbreviation" => array("type" => "string"),
        "Name" => array("type" => "string"),
        "Abbreviation" => array("type" => "string"),
    );
    public function __construct($data) {
        $this->set_data($data);
    }
}
