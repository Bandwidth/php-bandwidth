<?php

namespace Iris;

class Status {
    use BaseModel;

    protected $fields = array(
        "Code" => array("type" => "string"),
        "Description" => array("type" => "string"),
        "Status" => array("type" => "string"),
    );

    public function __construct($data) {
        $this->set_data($data);
    }
}
