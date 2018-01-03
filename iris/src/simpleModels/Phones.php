<?php

namespace Iris;

class Phones {
    use BaseModel;

    protected $fields = array(
        "PhoneNumber" => array("type" => "string"),
        "TelephoneNumber" => array("type" => "string"),
        "FullNumber" => array("type" => "string")
    );

    public function __construct($data) {
        $this->set_data($data, true);
    }
}
