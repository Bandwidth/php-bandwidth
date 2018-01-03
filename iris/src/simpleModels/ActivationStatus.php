<?php

namespace Iris;

class ActivationStatus {
    use BaseModel;

    protected $fields = array(
        "AutoActivationDate" => array("type" => "string"),
        "ActivatedTelephoneNumbersList" => array("type" => "Iris\Phones"),
        "NotYetActivatedTelephoneNumbersList" => array("type" => "Iris\Phones")
    );

    public function __construct($data) {
        $this->set_data($data);
    }
}
