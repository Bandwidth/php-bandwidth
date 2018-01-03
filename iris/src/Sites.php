<?php

/**
 * @model Sites
 *
 * provides:
 * get/0
 *
 */

namespace Iris;

class Sites extends RestEntry {
    public function __construct($parent) {
        $this->parent = $parent;
        parent::_init($this->parent->get_rest_client(), $this->parent->get_relative_namespace());
    }

    public function getList($filters = Array()) {
        $sites = [];

        $data = parent::_get('sites');

        if(isset($data["Sites"]) && isset($data["Sites"]["Site"])) {
            $items = $data["Sites"]["Site"];

            if(is_array($items) && $this->is_assoc($items))
                $items = [ $items ];

            foreach($items as $site) {
                $sites[] = new Site($this, $site);
            }
        }

        return $sites;
    }

    public function site($id) {
        $site = new Site($this, array("Id" => $id));
        $site->get();
        return $site;
    }

    public function get_appendix() {
        return '/sites';
    }

    public function create($data, $save = true) {
        $site = new Site($this, $data);
        if($save)
            $site->save();
        return $site;
    }
}

class Site extends RestEntry {
    use BaseModel;

    protected $fields = array(
        "Id" => array(
            "type" => "string"
        ),
        "Name" => array(
            "type" => "string"
        ),
        "Description" => array(
            "type" => "string"
        ),
        "Address" => array(
            "type" => "\Iris\Address"
        ),
        "CustomerProvidedID" => array(
            "type" => "string"
        ),
        "CustomerName" => array(
            "type" => "string"
        )
    );

    public function __construct($sites, $data) {
        $this->set_data($data);
        $this->parent = $sites;
        parent::_init($sites->get_rest_client(), $sites->get_relative_namespace());
    }

    public function get() {
        $data = parent::_get($this->get_id());
        $this->set_data($data['Site']);
    }
    public function delete() {
        parent::_delete($this->get_id());
    }

    public function save() {
        $header = parent::post(null, "Site", $this->to_array());
        $splitted = explode("/", $header['Location']);
        $this->Id = end($splitted);
    }

    public function update() {
        parent::put($this->get_id(), "Site", $this->to_array());
    }

    public function totaltns() {
        $url = sprintf('%s/%s', $this->get_id(), "totaltns");
        $data = parent::_get($url);
        return $data['SiteTNs']['TotalCount'];
    }

    public function sippeers() {
        if(!isset($this->sippeers))
            $this->sippeers = new Sippeers($this);
        return $this->sippeers;
    }

    public function portins() {
        if(!isset($this->portins))
            $this->portins = new Portins($this);
        return $this->portins;
    }

    public function orders() {
        if(!isset($this->orders))
            $this->orders = new Orders($this);
        return $this->orders;
    }

    public function get_id() {
        if(!isset($this->Id))
            throw new \Exception('Id should be provided');
        return $this->Id;
    }

    public function get_appendix() {
        return '/'.$this->get_id();
    }
}
