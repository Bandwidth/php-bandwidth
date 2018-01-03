<?php

namespace Iris;

class OrderRequestStatus {
    use BaseModel;

    protected $fields = array(
        "orderRequest" => array("type" => "\Iris\OrderRequest"),
        "OrderStatus" => array("type" => "string")
    );

    public function __construct($data) {
        $this->set_data($data, true);
    }
}

class OrderRequest {
    use BaseModel;

    protected $fields = array(
        "CustomerOrderId" => array("type" => "string"),
        "OrderCreateDate" => array("type" => "string"),
        "id" => array("type" => "string"),
        "DisconnectTelephoneNumberOrderType" => array("type" => "string"),
        "OrderCreateDate" => array("type" => "string"),
    );

    public function __construct($data) {
        $this->set_data($data, true);
    }
}
