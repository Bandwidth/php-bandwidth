<?php

namespace Iris;

class NumberPortabilityResponse {
    use BaseModel;

    protected $fields = array(
        "PortableNumbers" => array("type" => "\Iris\TnList"),
        "SupportedRateCenters" => array("type" => "string"),
        "UnsupportedRateCenters" => array("type" => "\Iris\RateCentersS"),
        "PartnerSupportedRateCenters" => array("type" => "\Iris\RateCentersS"),
        "SupportedLosingCarriers" => array("type" => "\Iris\SupportedLosingCarriers")
    );

    public function __construct($data) {
        $this->set_data($data, true);
    }
}

class RateCentersS {
    use BaseModel;

    protected $fields = array(
        "RateCenterGroup" => array("type" => "\Iris\RateCenterGroup"),
    );

    public function __construct($data) {
        $this->set_data($data, true);
    }
}

class RateCenterGroup {
    use BaseModel;

    protected $fields = array(
        "RateCenter" => array("type" => "string"),
        "City" => array("type" => "string"),
        "State" => array("type" => "string"),
        "LATA" => array("type" => "string"),
        "TnList" => array("type" => "\Iris\TnList"),
        "Tiers" => array("type" => "\Iris\Tiers")
    );

    public function __construct($data) {
        $this->set_data($data, true);
    }
}

class SupportedLosingCarriers {
    use BaseModel;

    protected $fields = array(
        "LosingCarrierTnList" => array("type" => "\Iris\LosingCarrierTnList"),
    );

    public function __construct($data) {
        $this->set_data($data, true);
    }
}

class LosingCarrierTnList {
    use BaseModel;

    protected $fields = array(
        "LosingCarrierSPID" => array("type" => "string"),
        "LosingCarrierName" => array("type" => "string"),
        "LosingCarrierIsWireless" => array("type" => "string"),
        "LosingCarrierAccountNumberRequired" => array("type" => "string"),
        "LosingCarrierMinimumPortingInterval" => array("type" => "string"),
        "TnList" => array("type" => "\Iris\TnList"),
    );

    public function __construct($data) {
        $this->set_data($data, true);
    }
}
