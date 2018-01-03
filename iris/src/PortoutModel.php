<?php

/**
 * @model Portout
 * https://api.test.inetwork.com/v1.0/accounts/portouts
 *
 *
 *
 * provides:
 * get/0
 *
 */

namespace Iris;

final class Portouts extends RestEntry{

    public function __construct($parent) {
        $this->parent = $parent;
        parent::_init($this->parent->get_rest_client(), $this->parent->get_relative_namespace());
    }

    public function getList($filters = Array()) {
        $out = [];

        $portouts = parent::_get('portouts', $filters, Array("page"=> 1, "size" => 30), Array("page", "size"));

        if($portouts['lnpPortInfoForGivenStatus']) {
            $items = $portouts['lnpPortInfoForGivenStatus'];

            if($this->is_assoc($items)) {
                $items = [ $items ];
            }

            foreach($items as $portout) {
                $out[] = new Portin($this, $portout);
            }
        }

        return $out;
    }

    /**
    * Create new Portin
    * @params array $data
    * @return \Iris\Portin
    */
    public function create($data, $save = true) {
        $portout = new Portout($this, $data);
        if($save)
            $portout->save();
        return $portout;
    }

    public function portout($id)
    {
        $portouts = new Portout($this, ["OrderId" => $id]);
        $portouts->get();
        return $portouts;
    }

    public function get_appendix() {
        return '/portouts';
    }

}

class Portout extends RestEntry {
    use BaseModel;

    protected $fields = array(
        "CountOfTNs" => [ "type" => "string"],
        "lastModifiedDate" => [ "type" => "string"],
        "OrderDate" => [ "type" => "string"],
        "OrderType" => [ "type" => "string"],
        "LNPLosingCarrierId" => [ "type" => "string"],
        "LNPLosingCarrierName" => [ "type" => "string"],
        "RequestedFOCDate" => [ "type" => "string"],
        "VendorId" => [ "type" => "string"],
        "VendorName" => [ "type" => "string"],
        "PON" => [ "type" => "string"],
        "AccountId" => [ "type" => "string"],
        "PeerId" => [ "type" => "string"],
        "OrderCreateDate" => [ "type" => "string"],
        "LastModifiedBy"  => [ "type" => "string"],
        "PartialPort"  => [ "type" => "string"],
        "Immediately" =>  [ "type" => "string"],
        "OrderId" => array("type" => "string"),
        "Status" => array("type" => "\Iris\Status"),
        "Errors" => array("type" => "string"),
        "ProcessingStatus" => array("type" => "string"),
        "CustomerOrderId" => array("type" => "string"),
        "RequestedFocDate" => array("type" => "string"),
        "AlternateSpid" => array("type" => "string"),
        "WirelessInfo" => array("type" => "\Iris\WirelessInfo"),
        "LosingCarrierName" => array("type" => "string"),
        "LastModifiedDate" => array("type" => "string"),
        "userId" => array("type" => "string"),
        "BillingTelephoneNumber" => array("type" => "string"),
        "Subscriber" => array("type" => "\Iris\Subscriber"),
        "LoaAuthorizingPerson" => array("type" => "string"),
        "ListOfPhoneNumbers" => array("type" => "\Iris\Phones"),
        "SiteId" => array("type" => "string"),
        "Triggered" => array("type" => "string"),
        "BillingType" => array("type" => "string")
    );

    public function __construct($parent, $data) {
        $this->set_data($data);
        $this->parent = $parent;
        parent::_init($parent->get_rest_client(), $parent->get_relative_namespace());
        $this->notes = null;
    }

    public function get() {
        $data = parent::_get($this->get_id());
        $this->set_data($data);
    }

    public function totals($filters = array()) {
        $url = sprintf('%s/%s', $this->get_id(), 'totals');
        $response = parent::_get($url, $filters);
        return $response['Count'];
    }


    /**
    * Get Notes of Entity
    * @return \Iris\Notes
    */
    public function notes() {
        if(is_null($this->notes))
            $this->notes = new Notes($this);
        return $this->notes;
    }
    /**
     * Get Entity Id
     * @return type
     * @throws Exception in case of OrderId is null
     */
    private function get_id() {
        if(!isset($this->OrderId))
            throw new Exception("You can't use this function without OrderId");
        return $this->OrderId;
    }
    /**
     * Provide relative url
     * @return string
     */
    public function get_appendix() {
        return '/'.$this->get_id();
    }

}
