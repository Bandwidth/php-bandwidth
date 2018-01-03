<?php

namespace Iris;

class NumberPortabilityRequest {
    use BaseModel;

    protected $fields = array(
        "TnList" => array("type" => "\Iris\TnList"),
    );

    public function __construct($data) {
        $this->set_data($data, true);
    }
}
