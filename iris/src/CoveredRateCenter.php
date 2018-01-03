<?php

namespace Iris;

final class CoveredRateCenters extends RestEntry{
    public function __construct($client)
    {
        $this->client = $client;
        parent::_init($client, '');
    }

    public function getList($filters = Array()) {
        $rcs = [];
        $data = parent::_get('coveredRateCenters', $filters, Array("page"=> 1, "size" => 30), Array("page", "size"));

        if($data['CoveredRateCenter']) {
            $items =  $data['CoveredRateCenter'];

            if($this->is_assoc($items))
                $items = [ $items ];

            foreach($items as $rc) {
                $rcs[] = new CoveredRateCenter($this, $rc);
            }
        }
        return $rcs;
    }

    public function covered_rate_center($id) {
        $rc = new CoveredRateCenter($this, array("Id" => $id));
        $rc->get();
        return $rc;
    }

    public function get_rest_client() {
        return $this->client;
    }

    public function get_relative_namespace() {
        return '/coveredRateCenters';
    }
}

final class CoveredRateCenter extends RestEntry{
    use BaseModel;

    protected $fields = [
        "Name" => [ "type" => "string" ],
        "Abbreviation" => [ "type" => "string" ],
        "State" => [ "type" => "string" ],
        "Lata" => [ "type" => "string" ],
        "AvailableNumberCount" => [ "type" => "string" ],
        "ZipCodes" => [ "type" => "\Iris\ZipCodes" ],
        "Cities" => [ "type" => "\Iris\CitiesS" ],
        "Tiers" => [ "type" => "\Iris\Tiers" ],
        "NpaNxxXs" => [ "type" => "\Iris\NpaNxxXs" ],
        "Id" => [ "type" => "string" ],
    ];

    public function __construct($parent, $data) {
        $this->set_data($data);
        $this->parent = $parent;
        parent::_init($parent->get_rest_client(), $parent->get_relative_namespace());
    }

    public function get() {
        $data = parent::_get($this->get_id());
        $data = $data['CoveredRateCenter'];
        $this->set_data($data);
    }

    public function get_id() {
        if(!isset($this->Id))
            throw new \Exception("You should set FullNumber");
        return $this->Id;
    }

}
