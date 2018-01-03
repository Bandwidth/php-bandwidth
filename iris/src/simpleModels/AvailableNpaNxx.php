<?php

namespace Iris;

class AvailableNpaNxx {
    use BaseModel;

    protected $fields = [
        "City" => ["type" => "string"],
        "Npa" => ["type" => "string"],
        "Nxx" => ["type" => "string"],
        "Quantity" => ["type" => "string"],
        "State" => ["type" => "string"],
    ];
    public function __construct($data) {
        $this->set_data($data);
    }
}
