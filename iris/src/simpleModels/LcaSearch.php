<?php

namespace Iris;

class LcaSearch {
    use BaseModel;

    protected $fields = array(
        "ListofNPANXX" => array("type" => "\Iris\NPANXX"),
        "Location" => array("type" => "\Iris\Location"),
    );
    public function __construct($data) {
        $this->set_data($data);
    }
}

class NPANXX {
    use BaseModel;

    protected $fields = array(
        "NPANXX" => array("type" => "string"),
    );
    public function __construct($data) {
        $this->set_data($data);
    }
}

class Location {
    use BaseModel;

    protected $fields = array(
        "RateCenters" => array("type" => "\Iris\LcaRateCenters"),
    );
    public function __construct($data) {
        $this->set_data($data);
    }
}

class LcaRateCenters {
    use BaseModel;

    protected $fields = array(
        "State" => array("type" => "string"),
        "RCs" => array("type" => "string"),
    );
    public function __construct($data) {
        $this->set_data($data);
    }
}
