<?php

namespace Iris;

class DldaTnGroups {
    use BaseModel;

    protected $fields = array(
        "DldaTnGroup" => array("type" => "\Iris\DldaTnGroup")
    );
    public function __construct($data) {
        $this->set_data($data);
    }
}

class DldaTnGroup {
    use BaseModel;

    protected $fields = array(
        "TelephoneNumbers" => array("type" => "\Iris\Phones"),
        "AccountType" => array("type" => "string"),
        "ListingType" => array("type" => "string"),
        "ListAddress" => array("type" => "string"),
        "ListingName" => array("type" => "\Iris\ListingName"),
        "Address" => array("type" => "\Iris\ServiceAddress"),
    );
    public function __construct($data) {
        $this->set_data($data);
    }
}

class ListingName {
    use BaseModel;

    protected $fields = array(
        "FirstName" => array("type" => "string"),
        "FirstName2" => array("type" => "string"),
        "LastName" => array("type" => "string"),
        "Designation" => array("type" => "string"),
        "TitleOfLineage" => array("type" => "string"),
        "TitleOfAddress" => array("type" => "string"),
        "TitleOfAddress2" => array("type" => "string"),
        "TitleOfLineageName2" => array("type" => "string"),
        "TitleOfAddressName2" => array("type" => "string"),
        "TitleOfAddress2Name2" => array("type" => "string"),
        "PlaceListingAs" => array("type" => "string"),

    );
    public function __construct($data) {
        $this->set_data($data);
    }
}
