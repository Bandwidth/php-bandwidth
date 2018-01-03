<?php

namespace Iris;

final class Users extends RestEntry{
    public function __construct($parent, $client=null, $namespace="")
    {
        if($parent) {
            $this->parent = $parent;
            parent::_init($this->parent->get_rest_client(), $this->parent->get_relative_namespace());
        }
        else {
            parent::_init($client, $namespace="");
        }
    }

    public function getList()
    {
        $out = [];
        $url = sprintf('%s', 'users');
        $data = parent::_get($url);

        if(isset($data['Users']) && isset($data['Users']['User'])) {
            $items = $data['Users']['User'];

            if($this->is_assoc($items)) {
                $items = [ $items ];
            }

            foreach($items as $item) {
                $out[] = new User($this->parent->get_rest_client(), $item);
            }
        }

        return $out;
    }
}

final class User extends RestEntry {
    use BaseModel;

    protected $fields = [
        "Username" => ["type" => "string"],
        "FirstName" => ["type" => "string"],
        "LastName" => ["type" => "string"],
        "EmailAddress" => ["type" => "string"],
        "TelephoneNumber" => ["type" => "string"],
        "Roles" => ["type" => "\Iris\Roles"],
    ];

    public function __construct($client=null, $data)
    {
        $this->set_data($data);
        parent::_init($client, "users");
    }
    public function password($password)
    {
        $url = sprintf('%s/%s', $this->get_id(), 'password');
        parent::put($url, 'Password', htmlspecialchars($password));
    }

    public function get_id() {
        if(!isset($this->Username))
            throw new \Exception("You should set Username");
        return $this->Username;
    }

}
