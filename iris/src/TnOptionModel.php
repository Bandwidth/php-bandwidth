<?php

/**
 * @model tnoptions
 * https://api.test.inetwork.com/v1.0/accounts/{accountId}/tnoptions
 *
 *
 *
 * provides:
 * get/0
 *
 */

namespace Iris;

final class TnOptions extends RestEntry{

    public function __construct($parent) {
        $this->parent = $parent;
        parent::_init($this->parent->get_rest_client(), $this->parent->get_relative_namespace());
    }

    public function getList($filters = Array()) {

        $list = [];

        $data = parent::_get('tnoptions');

        if($data['TnOptionOrderSummary'] && $data['TotalCount'] > 0) {
            $items = $data['TnOptionOrderSummary'];

            if($this->is_assoc($items))
                $items = [ $items ];

            foreach($items as $item) {
                $list[] = new TnOption($this, $item);
            }
        }

        return $list;
    }

    public function tnoption($id) {
        $item = new TnOption($this, array("OrderId" => $id));
        $item->get();
        return $item;
    }

    public function get_appendix() {
        return '/tnoptions';
    }

    public function create($data, $save = true) {
        $item = new TnOption($this, $data);
        if($save)
            return $item->save();
        return $item;
    }
}

final class TnOption extends RestEntry{
    use BaseModel;

    protected $fields = array(
        "OrderId" => array("type" => "string"),
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
        "ErrorList" => array("type" => "\Iris\ErrorList"),
        "TnOptionGroups" => array("type" => "\Iris\TnOptionGroups"),
    );


    public function __construct($parent, $data)
    {
        $this->set_data($data);
        $this->parent = $parent;
        parent::_init($parent->get_rest_client(), $parent->get_relative_namespace());
    }

    public function get_id() {
        if(!isset($this->OrderId) && !isset($this->orderId))
            throw new \Exception('Id should be provided');
        return isset($this->OrderId) ? $this->OrderId: $this->orderId;
    }

    public function get() {
        $data = parent::_get($this->get_id());
        $this->set_data($data);
    }

    public function save() {
        $data = parent::post(null, "TnOptionOrder", $this->to_array());
        return new TnOption($this->parent, $data['TnOptionOrder']);
    }

    public function history() {
        $url = sprintf("%s/%s", $this->get_id(), "history");
        $data = parent::_get($url);
        return new History($data);
    }

}
