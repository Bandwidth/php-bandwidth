<?php

namespace Iris;

class MessageSettings {
    use BaseModel;

    protected $fields = array(
        "SmsEnabled" => array("type" => "string")
    );
    public function __construct($data) {
        $this->set_data($data);
    }
}
