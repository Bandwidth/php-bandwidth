<?php

class TestPestXML {
  public function __construct() {
      $this->curl_opts = array();
      $this->response = null;
  }
  public function setStringResponse($response) {
    $this->response = $response;
  }
  public function get($url, $options = array()) {
    $this->url = $url;
    $this->options = $options;
    return simplexml_load_string($this->response);
  }
}

class TestClient extends Iris\PestClient
{
  public function __construct($login, $password, $options=Null)
  {
      parent::__construct($login, $password, $options);
      $this->pest = new TestPestXML();
  }

  public function setStringResponse($string)
  {
    $this->pest->setStringResponse($string);
  }
  public function getUrl() {
    return $this->pest->url;
  }
  public function getOptions() {
    return $this->pest->options;
  }
}
