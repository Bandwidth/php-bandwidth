<?php

namespace Iris;

class NpaNxxXs {
    use BaseModel;

    protected $fields = array(
        "NpaNxxX" => array("type" => "string")
    );
    public function __construct($data) {
        $this->set_data($data);
    }
}
