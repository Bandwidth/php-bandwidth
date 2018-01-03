<?php

namespace Iris;

class ErrorList {
    use BaseModel;

    protected $fields = array(
        "Error" => array("type" => "\Iris\Error")
    );
    public function __construct($data) {
        $this->set_data($data);
    }
}

class Error {
    use BaseModel;

    protected $fields = array(
        "TelephoneNumber" => array("type" => "string"),
        "ErrorCode" => array("type" => "string"),
        "Code" => array("type" => "string"),
        "Description" => array("type" => "string")
    );

    public function __construct($data) {
        $this->set_data($data);
    }
}
