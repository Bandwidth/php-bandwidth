<?php

/**
 * @model Dldas
 * https://api.test.inetwork.com/v1.0/accounts/dldas
 *
 *
 *
 * provides:
 * get/0
 *
 */

namespace Iris;

final class Dldas extends RestEntry{

    public function __construct($parent) {
        $this->parent = $parent;
        parent::_init($this->parent->get_rest_client(), $this->parent->get_relative_namespace());
    }

    public function getList($filters = Array()) {

        $dldas = [];

        $data = parent::_get('dldas');

        if($data['ListOrderIdUserIdDate'] && $data['ListOrderIdUserIdDate']['TotalCount']) {
            $items = $data['ListOrderIdUserIdDate']['OrderIdUserIdDate'];

            if($this->is_assoc($items))
                $items = [ $items ];

            foreach($items as $dlda) {
                $dldas[] = new Dlda($this, $dlda);
            }
        }

        return $dldas;
    }

    public function dlda($id) {
        $dlda = new Dlda($this, array("OrderId" => $id));
        $dlda->get();
        return $dlda;
    }

    public function get_appendix() {
        return '/dldas';
    }

    public function create($data, $save = true) {
        $dlda = new Dlda($this, $data);
        if($save)
            return $dlda->save();
        return $dlda;
    }
}

final class Dlda extends RestEntry{
    use BaseModel;

    protected $fields = array(
        "CustomerOrderId" => array("type" => "string"),
        "OrderCreateDate" => array("type" => "string"),
        "AccountId" => array("type" => "string"),
        "CreatedByUser" => array("type" => "string"),
        "OrderId" => array("type" => "string"),
        "LastModifiedDate" => array("type" => "string"),
        "ProcessingStatus" => array("type" => "string"),
        "DldaTnGroups" => array("type" => "\Iris\DldaTnGroups"),

        "accountId" => array("type" => "string"),
        "CountOfTNs" => array("type" => "string"),
        "userId" => array("type" => "string"),
        "lastModifiedDate" => array("type" => "string"),
        "OrderType" => array("type" => "string"),
        "OrderDate" => array("type" => "string"),
        "orderId" => array("type" => "string"),
        "OrderStatus" => array("type" => "string"),

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
        $this->set_data($data['DldaOrder']);
    }

    public function save() {
        $data = parent::post(null, "DldaOrder", $this->to_array());
        return new Dlda($this->parent, $data['DldaOrder']);
    }

    public function update() {
        parent::put($this->get_id(), "DldaOrder", $this->to_array());
    }

    public function history() {
        $url = sprintf("%s/%s", $this->get_id(), "history");
        $data = parent::_get($url);
        return new History($data);
    }

}
