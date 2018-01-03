<?php

namespace Iris;

class Subscriber {
    use BaseModel;

    protected $fields = array(
        "SubscriberType" => array("type" => "string"),
        "BusinessName" => array("type" => "string"),
        "ServiceAddress" => array("type" => "\Iris\ServiceAddress"),
        "FirstName" => array("type" => "string"),
        "LastName" => array("type" => "string"),
        "MiddleInitial" => array("type" => "string")
    );

    public function __construct($data) {
        $this->set_data($data);
    }
}
