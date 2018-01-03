<?php

/**
 * @model Libd
 * https://api.test.inetwork.com/v1.0/accounts/libds
 *
 *
 *
 * provides:
 * get/0
 *
 */

namespace Iris;

final class Lidbs extends RestEntry{

    public function __construct($parent) {
        $this->parent = $parent;
        parent::_init($this->parent->get_rest_client(), $this->parent->get_relative_namespace());
    }

    public function getList($filters = Array()) {

        $libds = [];

        $data = parent::_get('lidbs', $filters);

        if($data['ListOrderIdUserIdDate'] && $data['ListOrderIdUserIdDate']['TotalCount'] > 0) {
            $items = $data['ListOrderIdUserIdDate']['OrderIdUserIdDate'];

            if($this->is_assoc($items))
                $items = [ $items ];

            foreach($items as $libd) {
                $libds[] = new Lidb($this, $libd);
            }
        }

        return $libds;
    }

    public function lidb($id) {
        $lidb = new Lidb($this, ["orderId" => $id]);
        $lidb->get();
        return $lidb;
    }

    public function create($data, $save = true) {
        $lidb = new Lidb($this, $data);
        if($save)
            $lidb->save();
        return $lidb;
    }

    public function get_appendix() {
        return '/lidbs';
    }
}

final class Lidb extends RestEntry{
    use BaseModel;

    protected $fields = array(
        "CustomerOrderId" => array("type" => "string"),
        "orderId" => array("type" => "string"),
        "AccountId" => array("type" => "string"),
        "accountId" => array("type" => "string"),
        "CountOfTNs" => array("type" => "string"),
        "userId" => array("type" => "string"),
        "LastModifiedDate" => array("type" => "string"),
        "lastModifiedDate" => array("type" => "string"),
        "OrderType" => array("type" => "string"),
        "OrderDate" => array("type" => "string"),
        "OrderStatus" => array("type" => "string"),
        "OrderCreateDate" => array("type" => "string"),
        "ProcessingStatus" => array("type" => "string"),
        "CreatedByUser" => array("type" => "string"),
        "OrderCompleteDate" => array("type" => "string"),
        "ErrorList" => array("type" => "\Iris\ErrorList"),
        "LidbTnGroups" => array("type" => "\Iris\LidbTnGroups"),
    );

    public function __construct($parent, $data)
    {
        parent::_init($parent->get_rest_client(), $parent->get_relative_namespace());

        $this->parent = $parent;
        $this->set_data($data);
    }

    public function get() {
        $data = parent::_get($this->get_id());
        $this->set_data($data);
    }

    public function save() {
        $data = parent::post(null, "LidbOrder", $this->to_array());
        if (isset($data["LidbOrder"])) {
            if (isset($data["LidbOrder"]["OrderId"])) {
                $data["LidbOrder"]["orderId"] = $data["LidbOrder"]["OrderId"];
                unset($data["LidbOrder"]["OrderId"]);
            }
            $this->set_data($data["LidbOrder"]);
        } else {
            $this->set_data($data);
        }
    }

    public function get_id() {
        if(is_null($this->orderId))
            throw new \Exception('Id should be provided');
        return $this->orderId;
    }
}
