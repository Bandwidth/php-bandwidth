<?php

namespace Iris;

abstract class RestEntry{

    protected $client = Null;
    protected $namespace = Null;
    protected $fields = array();
    protected $required = array();

    protected function _init($client, $namespace)
    {
        if (!$client)
        {
            $this->client = new GuzzleClient(\Iris\Config::REST_LOGIN, \Iris\Config::REST_PASS, Array('url' => \Iris\Config::REST_URL));
        }
        else
        {
            $this->client = $client;
        }
        if (!is_null($namespace))
        {
            $this->namespace = $namespace;
        }
        else
        {
            $this->namespace = strtolower(get_class($this));
        }
    }

    protected function is_assoc($array) {
        $array = array_keys($array); return ($array !== array_keys($array));
    }

    protected function get_url($path)
    {
        if(is_null($path))
            return $this->namespace;

        return sprintf('%s/%s', $this->namespace, $path);
    }

    protected function _get($url, $options=Array(), $defaults = Array(), $required = Array())
    {
        $url = $this->get_url($url);
        $this->set_defaults($options, $defaults);
        $this->check_required($options, $required);

        return $this->client->get($url, ['query' => $options]);
    }

    public function raw_file_post($url, $body, $headers = array()) {
        $url = $this->get_url($url);
        return $this->client->raw_file_post($url, $body, $headers);
    }
    public function raw_file_put($url, $body, $headers = array()) {
        $url = $this->get_url($url);
        return $this->client->raw_file_put($url, $body, $headers);
    }

    protected function post($url, $base_node, $data)
    {
        $url = $this->get_url($url);
        return $this->client->post($url, $base_node, $data);
    }

    protected function put($url, $base_node, $data)
    {
        $url = $this->get_url($url);
        return $this->client->put($url, $base_node, $data);
    }

    protected function _delete($url)
    {
        $url = $this->get_url($url);
        $this->client->delete($url);
    }

    protected function set_defaults(&$options, $defaults) {
        foreach($defaults as $key => $value) {
            if(!array_key_exists($key, $options))
                $options[$key] = $value;
        }
    }

    protected function check_required($options, $required) {
        foreach($required as $key) {
            if(!array_key_exists($key, $options))
                throw new ValidateException("Required options '{$key}' should be provided");
        }
    }

    public function get_rest_client() {
        return $this->parent->get_rest_client();
    }
    public function get_relative_namespace() {
        return $this->parent->get_relative_namespace().$this->get_appendix();
    }

}
