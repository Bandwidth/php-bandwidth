<?php

namespace Iris;

class SipPeerTelephoneNumber {
    use BaseModel;

    protected $fields = array(
        "FullNumber" => array("type" => "string"),
        "CallForward" => array("type" => "string"),
        "NumberFormat" => array("type" => "string"),
        "RPIDFormat" => array("type" => "string"),
        "RewriteUser" => array("type" => "string")
    );

    public function __construct($data) {
        $this->set_data($data, true);
    }
}
