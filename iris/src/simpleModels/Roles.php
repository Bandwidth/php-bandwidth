<?php

namespace Iris;

class Roles {
    use BaseModel;

    protected $fields = array(
        "Role" => array("type" => "\Iris\Role"),
    );
    public function __construct($data) {
        $this->set_data($data);
    }
}

class Role {
    use BaseModel;

    protected $fields = array(
        "RoleName" => array("type" => "string"),
        "Permissions" => array("type" => "\Iris\Permissions"),
    );
    public function __construct($data) {
        $this->set_data($data);
    }
}

class Permissions {
    use BaseModel;

    protected $fields = array(
        "Permission" => array("type" => "\Iris\Permission"),
    );
    public function __construct($data) {
        $this->set_data($data);
    }
}

class Permission {
    use BaseModel;

    protected $fields = array(
        "PermissionName" => array("type" => "string"),
    );
    public function __construct($data) {
        $this->set_data($data);
    }
}
