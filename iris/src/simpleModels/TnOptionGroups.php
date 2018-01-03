<?php

namespace Iris;

class TnOptionGroups {
    use BaseModel;

    protected $fields = array(
        "TnOptionGroup" => array("type" => "\Iris\TnOptionGroup")
    );
    public function __construct($data) {
        $this->set_data($data);
    }
}

class TnOptionGroup {
    use BaseModel;

    protected $fields = array(
        "TelephoneNumbers" => array("type" => "\Iris\Phones"),
        "NumberFormat" => array("type" => "string"),
        "RPIDFormat" => array("type" => "string"),
        "RewriteUser" => array("type" => "string"),
        "CallForward" => array("type" => "string"),
        "CallingNameDisplay" => array("type" => "string"),
        "Protected" => array("type" => "string"),
        "Sms" => array("type" => "string"),
        "FinalDestinationURI " => array("type" => "string")
    );
    public function __construct($data) {
        $this->set_data($data);
    }
}