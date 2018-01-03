<?php

/**
 * @model Tnsreserv
 * https://api.test.inetwork.com/v1.0/accounts/tnsreservation
 *
 *
 *
 * provides:
 * get/0
 *
 */

namespace Iris;

final class TnsReservations extends RestEntry{

    public function __construct($parent) {
        $this->parent = $parent;
        parent::_init($this->parent->get_rest_client(), $this->parent->get_relative_namespace());
    }

    public function create($data, $save = true) {
        $tns_res = new TnsReservation($this, $data);
        if($save)
            $tns_res->save();
        return $tns_res;
    }

    public function tnsreservation($id) {
        $r = new TnsReservation($this, ["ReservationId" => $id]);
        return $r->get();
    }

    public function get_appendix() {
        return '/tnreservation';
    }
}

final class TnsReservation extends RestEntry{
    use BaseModel;

    protected $fields = array(
        "ReservedTn" => array("type" => "string"),
        "ReservationId" => array("type" => "string"),
        "ReservationExpires" => array("type" => "string"),
        "AccountId" => array("type" => "string")
    );

    public function __construct($parent, $data)
    {
        $this->set_data($data);
        $this->parent = $parent;
        parent::_init($parent->get_rest_client(), $parent->get_relative_namespace());
    }

    public function save() {
        $header = parent::post(null, "Reservation", $this->to_array());
        $splitted = explode("/", $header['Location']);
        $this->ReservationId = end($splitted);
    }

    public function get() {
        $data = parent::_get($this->get_id());
        $this->set_data($data['Reservation']);
        return $this;
    }

    public function delete() {
        parent::_delete($this->get_id());
    }

    public function get_id() {
        if(!isset($this->ReservationId))
            throw new \Exception('ReservationId should be provided');
        return $this->ReservationId;
    }

}
