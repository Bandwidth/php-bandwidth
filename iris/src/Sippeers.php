<?php

namespace Iris;

class Sippeers extends RestEntry {
    public function __construct($site) {
        $this->parent = $site;
        parent::_init($site->get_rest_client(), $site->get_relative_namespace());
    }

    public function getList($filters = Array()) {
        $sippeers = [];

        $data = parent::_get('sippeers');

        if(isset($data['SipPeers']) && isset($data['SipPeers']['SipPeer'])) {
            if($this->is_assoc($data['SipPeers']['SipPeer']))
                $peers = [ $data['SipPeers']['SipPeer'] ];
            else
                $peers = $data['SipPeers']['SipPeer'];

            foreach($peers as $sippeer) {
                $sippeers[] = new Sippeer($this, $sippeer);
            }
        }

        return $sippeers;
    }

    public function sippeer($id) {
        $sipper = new Sippeer($this, array("PeerId" => $id));
        $sipper->get();
        return $sipper;
    }

    public function get_appendix() {
        return '/sippeers';
    }

    public function create($data, $save = true) {
        $sippeer = new Sippeer($this, $data);
        if($save)
            $sippeer->save();
        return $sippeer;
    }
}

class Sippeer extends RestEntry {
    use BaseModel;

    protected $fields = array(
        "PeerId" => array("type" => "string"),
        "PeerName" => array("type" => "string"),
        "IsDefaultPeer" => array("type" => "string"),
        "ShortMessagingProtocol" => array("type" => "string"),
        "VoiceHosts" => array("type" => "Iris\Hosts"),
        "VoiceHostGroups" => array("type" => "string"),
        "SmsHosts" => array("type" => "Iris\Hosts"),
        "TerminationHosts" => array("type" => "Iris\Hosts"),
        "CallingName" => array("type" => "string")
    );

    public function __construct($parent, $data) {
        $this->PeerId = null;

        if(isset($data)) {
            if(is_object($data) && $data->PeerId)
                $this->PeerId = $data->PeerId;
            if(is_array($data) && isset($data['PeerId']))
                $this->PeerId = $data['PeerId'];
        }
        $this->set_data($data);

        $this->parent = $parent;
        parent::_init($parent->get_rest_client(), $parent->get_relative_namespace());
        $this->tns = null;
    }

    public function get() {
        $data = parent::_get($this->get_id());
        $this->set_data($data['SipPeer']);
    }

    public function totaltns() {
        $url = sprintf('%s/%s', $this->get_id(), "totaltns");
        $data = parent::_get($url);
        return $data['SipPeerTelephoneNumbersCounts']['SipPeerTelephoneNumbersCount'];
    }

    public function save() {
        $header = parent::post(null, "SipPeer", $this->to_array());
        $splitted = explode("/", $header['Location']);
        $this->PeerId = end($splitted);
    }

    public function update() {
        parent::put($this->get_id(), "SipPeer", $this->to_array());
    }

    public function delete() {
        parent::_delete($this->get_id());
    }

    public function movetns($data) {
        $data = new \Iris\Phones($data);
        $url = sprintf("%s/%s", $this->get_id(), "movetns");
        parent::post($url, "SipPeerTelephoneNumbers", $data);
    }

    public function tns() {
        if(is_null($this->tns))
            $this->tns = new Tns($this);
        return $this->tns;
    }

    private function get_id() {
        if(!isset($this->PeerId))
            throw new \Exception('Id should be provided');
        return $this->PeerId;
    }


    public function get_appendix() {
        return '/'.$this->get_id();
    }
}
