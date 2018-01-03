<?php

namespace Iris;

class TnList {
    use BaseModel;

    protected $fields = array(
        "Tn" => array("type" => "string"),
    );

    public function __construct($data) {
        $this->set_data($data, true);
    }
}
