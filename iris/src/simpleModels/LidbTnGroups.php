<?php

namespace Iris;

class LidbOrder {
    use BaseModel;

    protected $fields = array(
        "LidbTnGroups" => array("type" => "\Iris\LidbTnGroups")
    );
    public function __construct($data) {
        $this->set_data($data);
    }
}

class LidbTnGroups {
    use BaseModel;

    protected $fields = array(
        "LidbTnGroup" => array("type" => "\Iris\LidbTnGroup")
    );
    public function __construct($data) {
        $this->set_data($data);
    }
}

class LidbTnGroup {
    use BaseModel;

    protected $fields = array(
        "TelephoneNumbers" => array("type" => "\Iris\Phones"),
        "SubscriberInformation" => array("type" => "string"),
        "UseType" => array("type" => "string"),
        "Visibility" => array("type" => "string"),
        "FullNumber" => array("type" => "string"),

    );
    public function __construct($data) {
        $this->set_data($data);
    }
}
