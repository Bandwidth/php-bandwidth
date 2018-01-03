<?php

namespace Iris;

class History {
    use BaseModel;

    protected $fields = array(
        "OrderHistory" => array("type" => "\Iris\OrderHistory")
    );
    public function __construct($data) {
        $this->set_data($data);
    }
}

class OrderHistory {
    use BaseModel;

    protected $fields = array(
        "OrderDate" => array("type" => "string"),
        "Note" => array("type" => "string"),
        "Author" => array("type" => "string"),
        "Status" => array("type" => "string"),
        "Difference" => array("type" => "string"),
    );
    public function __construct($data) {
        $this->set_data($data);
    }
}
