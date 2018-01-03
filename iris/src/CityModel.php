<?php

namespace Iris;

final class Cities extends RestEntry{
    public function __construct($client)
    {
        $this->client = $client;
        parent::_init($client, '');
    }

    public function getList($filters = Array()) {
        $cities = [];
        $data = parent::_get('cities', $filters, Array(), Array("state"));

        if($data['Cities']) {
            $items =  $data['Cities']['City'];

            if($this->is_assoc($items))
                $items = [ $items ];

            foreach($items as $city) {
                $cities[] = new \Iris\CitiesS($city);
            }
        }
        return $cities;
    }
}
