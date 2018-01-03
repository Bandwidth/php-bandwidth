<?php

namespace Iris;

class Suggestions
{
    public function __construct($client)
    {
        $this->client = $client;
    }

    /**
     * Get suggestions by query
     *
     * @return string
     */
    public function get($query)
    {
        return $this->client->get('suggestions', ['query' => ['q' => $query]]);
    }
}
