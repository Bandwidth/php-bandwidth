<?php

/**
 * @model Subsction
 * https://api.test.inetwork.com/v1.0/accounts/subscriptions
 *
 *
 *
 * provides:
 * get/0
 *
 */

namespace Iris;

final class Subscriptions extends RestEntry{

    public function __construct($parent) {
        $this->parent = $parent;
        parent::_init($this->parent->get_rest_client(), $this->parent->get_relative_namespace());
    }

    public function getList($filters = Array()) {

        $subscriptions = [];

        $data = parent::_get('subscriptions', $filters);

        if($data['Subscriptions'] && $data['Subscriptions']['Subscription']) {
            $items = $data['Subscriptions']['Subscription'];

            if($this->is_assoc($items))
                $items = [ $items ];

            foreach($items as $subscription) {
                $subscriptions[] = new Subscription($this, $subscription);
            }
        }

        return $subscriptions;
    }

    public function subscription($id) {
        $sbc = new Subscription($this, array("SubscriptionId" => $id));
        $sbc->get();
        return $sbc;
    }

    public function get_appendix() {
        return '/subscriptions';
    }

    public function create($data, $save = true) {
        $sbc = new Subscription($this, $data);
        if($save)
            $sbc->save();
        return $sbc;
    }
}

final class Subscription extends RestEntry{
    use BaseModel;

    protected $fields = array(
        "SubscriptionId" => array("type" => "string"),
        "OrderType" => array("type" => "string"),
        "OrderId" => array("type" => "string"),
        "EmailSubscription" => array("type" => "\Iris\EmailSubscription"),
        "CallbackSubscription" => array("type" => "\Iris\CallbackSubscription")
    );


    public function __construct($subscriptions, $data)
    {
        $this->set_data($data);
        $this->parent = $subscriptions;
        parent::_init($subscriptions->get_rest_client(), $subscriptions->get_relative_namespace());
    }

    public function get() {
        $data = parent::_get($this->get_id());
        $this->set_data($data['Subscriptions']['Subscription']);
    }
    public function delete() {
        parent::_delete($this->get_id());
    }

    public function save() {
        $header = parent::post(null, "Subscription", $this->to_array());
        $splitted = explode("/", $header['Location']);
        $this->SubscriptionId = end($splitted);
    }

    public function update() {
        parent::put($this->get_id(), "Subscription", $this->to_array());
    }

    public function get_id() {
        if(!isset($this->SubscriptionId))
            throw new \Exception('Id should be provided');
        return $this->SubscriptionId;
    }
}
