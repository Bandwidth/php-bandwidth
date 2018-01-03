<?php

/**
 * @model Disconnects
 *
 */

namespace Iris;

class Disconnects extends RestEntry{
    public function __construct($parent) {
        $this->parent = $parent;
        parent::_init($this->parent->get_rest_client(), $this->parent->get_relative_namespace());
    }

    public function getList($filters = Array())
    {
        $disconnects = [];

        $data = parent::_get('disconnects', $filters, Array("page"=> 1, "size" => 30), Array("page", "size"));

        if(isset($data['ListOrderIdUserIdDate']) && isset($data['ListOrderIdUserIdDate']['OrderIdUserIdDate'])) {
            $items = $data['ListOrderIdUserIdDate']['OrderIdUserIdDate'];

            if($this->is_assoc($items))
                $items = [ $items ];

            foreach($items as $item) {
                $disconnects[] = new Disconnect($this, $item);
            }
        }
        return $disconnects;
    }

    public function disconnect($id, $tndetail = false) {
        $d = new Disconnect($this, array("OrderId" => $id));
        $d->get($tndetail);
        return $d;
    }

    /**
     * Create new disconnect
     * @param type $data
     * @return \Iris\Disconnect
     */
    public function create($data, $save = true) {
        $disconnect = new Disconnect($this, $data);
        if($save)
            $disconnect->save();
        return $disconnect;
    }

    /**
     * Provide path of url
     * @return string
     */
    public function get_appendix() {
        return '/disconnects';
    }

}


class Disconnect extends RestEntry {
    use BaseModel;

    protected $fields = array(
        "CountOfTNs" => [ "type" => "string" ],
        "userId" => [ "type" => "string" ],
        "lastModifiedDate" => [ "type" => "string" ],
        "OrderId" => [ "type" => "string" ],
        "OrderType" => [ "type" => "string" ],
        "OrderDate" => [ "type" => "string" ],
        "OrderStatus" => [ "type" => "string" ],
        "TelephoneNumberDetails" => [ "type" => "\Iris\TelephoneNumberDetail" ],
        "name" => [ "type" => "string" ],
        "CustomerOrderId" => [ "type" => "string" ],
        "DisconnectTelephoneNumberOrderType" => [ "type" => "\Iris\TelephoneNumberList" ],
        "OrderStatus" => [ "type" => "string" ],
        "OrderCreateDate" => [ "type" => "string" ],

    );

    /**
     * Constructor
     * @param type $parent
     * @param type $data
     */
    public function __construct($parent, $data)
    {
        $this->parent = $parent;
        parent::_init($parent->get_rest_client(), $parent->get_relative_namespace());
        $this->set_data($data);
        $this->notes = null;
    }

    public function get($tndetail = false) {
        if($tndetail) {
            $options = [ "tndetail" => "true" ];
        } else {
            $options = [];
        }
        $data = parent::_get($this->get_id(), $options);
        $response = new OrderResponse($data);
        if(isset($data['orderRequest']))
            $this->set_data($data['orderRequest']);
        $response->orderRequest = $this;
        return $response;
    }

    /**
     * Make POST request
     */
    public function save() {
        $data = parent::post(null, "DisconnectTelephoneNumberOrder", $this->to_array());
        $this->OrderStatus = new OrderRequestStatus($data);
        if(isset($this->OrderStatus->orderRequest)) {
            $this->OrderId = $this->OrderStatus->orderRequest->id;
            $this->set_data($this->OrderStatus->orderRequest->to_array());
        }
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
    * Get Notes of Entity
    * @return \Iris\Notes
    */
    public function notes() {
        if(is_null($this->notes))
            $this->notes = new Notes($this);
        return $this->notes;
    }
    /**
     * Provide relative url
     * @return string
     */
    public function get_appendix() {
        return '/'.$this->get_id();
    }
}
