<?php

namespace Iris;

class Features {
    use BaseModel;

    protected $fields = array(
        "E911" => array("type" => "\Iris\Feature"),
        "Lidb" => array("type" => "\Iris\Feature"),
        "Dlda" => array("type" => "\Iris\Feature"),
    );
    public function __construct($data) {
        $this->set_data($data);
    }
}

class Feature {
    use BaseModel;

    protected $fields = array(
        "Status" => array("type" => "string"),
        "SubscriberType" => array("type" => "string"),
        "SubscriberInformation" => array("type" => "string"),
        "UseType" => array("type" => "string"),
        "Visibility" => array("type" => "string"),
        "ListingType" => array("type" => "string"),
        "ListingName" => array("type" => "\Iris\ListingName"),
        "ListAddress" => array("type" => "string"),
        "Address" => array("type" => "\Iris\ServiceAddress"),

    );
    public function __construct($data) {
        $this->set_data($data);
    }
}
