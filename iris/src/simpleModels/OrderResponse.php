<?php

namespace Iris;

class OrderResponse {
    use BaseModel;

    protected $fields = array(
        "CompletedQuantity" => array("type" => "string"),
        "CreatedByUser" => array("type" => "string"),
        "ErrorList" => array("type" => "\Iris\ErrorList"),
        "FailedNumbers" => array("type" => "\Iris\Phones"),
        "LastModifiedDate" => array("type" => "string"),
        "OrderCompleteDate" => array("type" => "string"),
        "OrderStatus" =>  array("type" => "string"),
        "CompletedNumbers" => array("type" => "\Iris\Phones"),
        "FailedQuantity" => array("type" => "string")
    );

    public function __construct($data) {
        $this->set_data($data, true);
    }
}
