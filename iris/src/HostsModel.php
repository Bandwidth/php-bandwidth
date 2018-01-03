<?php

namespace Iris;

class Hosts {
    use BaseModel;

    protected $fields = array(
        "Host" => array("type" => "\Iris\Host"),
        "TerminationHost" => array("type" => "\Iris\Host")
    );

    public function __construct($data) {
        $this->set_data($data);
    }
}

class Host {
    use BaseModel;

    protected $fields = array(
        "HostName" => array("type" => "string"),
        "Port" => array("type" => "string"),
        "CustomerTrafficAllowed" => array("type" => "string"),
        "DataAllowed" => array("type" => "string")
    );

    public function __construct($data) {
        $this->set_data($data);
    }
}
