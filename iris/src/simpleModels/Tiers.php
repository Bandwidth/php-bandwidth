<?php

namespace Iris;

class Tiers {
    use BaseModel;

    protected $fields = array(
        "Tier" => array("type" => "string")
    );
    public function __construct($data) {
        $this->set_data($data);
    }
}
