<?php

namespace Iris;

class ZipCodes {
    use BaseModel;

    protected $fields = array(
        "ZipCode" => array("type" => "string")
    );
    public function __construct($data) {
        $this->set_data($data);
    }
}
