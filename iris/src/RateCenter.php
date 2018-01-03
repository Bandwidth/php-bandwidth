<?php

namespace Iris;

final class RateCenter extends RestEntry{
    public function __construct($client)
    {
        $this->client = $client;
        parent::_init($client, '');
    }

    public function getList($filters = Array()) {
        $rcs = [];
        $data = parent::_get('rateCenters', $filters, Array(), Array("state"));

        if($data['RateCenters']) {
            $items =  $data['RateCenters']['RateCenter'];

            if($this->is_assoc($items))
                $items = [ $items ];

            foreach($items as $rc) {
                $rcs[] = new \Iris\CitiesS($rc);
            }
        }
        return $rcs;
    }
}
