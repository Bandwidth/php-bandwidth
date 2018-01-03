<?php

namespace Iris;

class TnAttributes {
    use BaseModel;

    protected $fields = array(
        "TnAttribute" => array("type" => "string")
    );
    public function __construct($data) {
        $this->set_data($data);
    }
}
