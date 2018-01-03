<?php

namespace Iris;

class CallbackSubscription {
    use BaseModel;

    protected $fields = array(
        "URL" => array("type" => "string"),
        "Expiry" => array("type" => "string"),
        "Status" => array("type" => "string"),
        "CallbackCredentials" => array("type" => "string"),
    );
    public function __construct($data) {
        $this->set_data($data);
    }
}
