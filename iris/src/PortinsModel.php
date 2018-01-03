<?php

/**
 * @model Portins
 *
 * provides:
 * get/0
 *
 */

namespace Iris;

class Portins extends RestEntry {
    public function __construct($account) {
        $this->parent = $account;
        parent::_init($account->get_rest_client(), $account->get_relative_namespace());
    }

    public function portin($id) {
        $portin = new Portin($this, array("OrderId" => $id));
        $portin->get();
        return $portin;
    }


    public function getList($filters = Array()) {
        $out = [];

        $portins = parent::_get('portins', $filters, Array("page"=> 1, "size" => 30), Array("page", "size"));

        if($portins['lnpPortInfoForGivenStatus']) {
            $items = $portins['lnpPortInfoForGivenStatus'];

            if($this->is_assoc($items)) {
                $items = [ $items ];
            }

            foreach($items as $portin) {
                $out[] = new Portin($this, $portin);
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
        $portin = new Portin($this, $data);
        if($save)
            $portin->save();
        return $portin;
    }

    public function totals()
    {
        $url = sprintf('%s/%s', 'portins', 'totals');
        $data = parent::_get($url);
        return $data;
    }

    public function get_appendix() {
        return '/portins';
    }
}

class Portin extends RestEntry {
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

    public function save() {
        $data = parent::post(null, "LnpOrder", $this->to_array());
        $this->set_data($data);
    }

    public function update() {
        $data = parent::put($this->get_id(), "LnpOrderSupp", $this->to_array());
        $this->set_data($data);
    }

    public function delete() {
        parent::_delete($this->get_id());
    }

    public function loas_send($file, $headers) {
        $url = sprintf('%s/%s', $this->get_id(), 'loas');
        $content = file_get_contents($file);
        return trim(parent::raw_file_post($url, $content, $headers));
    }

    public function loas_update($file, $filename, $headers) {
        $content = file_get_contents($file);
        $url = sprintf('%s/%s/%s', $this->get_id(), 'loas', $filename);
        parent::raw_file_put($url, $content, $headers);
    }

    public function loas_delete($filename) {
        $url = sprintf('%s/%s/%s', $this->get_id(), 'loas', $filename);
        parent::_delete($url);
    }

    public function list_loas($metadata) {
        $url = sprintf('%s/%s', $this->get_id(), 'loas');
        $query = array();

        if($metadata) {
            $query['metadata'] = 'true';
        }

        return (object)parent::_get($url, $query);
    }

    public function get_metadata($filename) {
        $url = sprintf('%s/%s/%s/metadata', $this->get_id(), 'loas', $filename);
        $data = parent::_get($url);
        return new FileMetaData($data);
    }

    public function set_metadata($filename, $meta) {
        $meta = new \Iris\FileMetaData($meta);
        $url = sprintf('%s/%s/%s/metadata', $this->get_id(), 'loas', $filename);
        parent::put($url, "FileMetaData", $meta->to_array());
    }

    public function delete_metadata($filename) {
        $url = sprintf('%s/%s/%s/metadata', $this->get_id(), 'loas', $filename);
        parent::_delete($url);
    }

    public function get_activation_status() {
        $url = sprintf('%s/%s', $this->get_id(), 'activationStatus');
        $data = parent::_get($url);
        return new ActivationStatus($data['ActivationStatus']);
    }

    public function set_activation_status($data) {
        $obj = new \Iris\ActivationStatus($data);
        $url = sprintf('%s/%s', $this->get_id(), 'activationStatus');
        $res = parent::post($url, "ActivationStatus", $obj->to_array());
        return new ActivationStatus($res['ActivationStatus']);
    }

    public function get() {
        $data = parent::_get($this->get_id());
        $this->set_data($data);
    }

    public function history() {
        $url = sprintf("%s/%s", $this->get_id(), "history");
        $data = parent::_get($url);
        return new History($data);
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
            throw new \Exception("You can't use this function without OrderId");
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
