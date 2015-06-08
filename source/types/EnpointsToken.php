<?php
/**
 * @type EndpointsToken
 *
 * Minimal interface to store
 * credentials. __toString should serialize
 */

namespace Catapult;

final class EndpointsToken{
  public function __construct() {
    $data = Ensure::Input(func_get_args());
    return $this->setup($data->get());
  }

  /**
   * check if we're
   * given the needed
   * keys. Additionally add
   * client checks
   *
   * token string
   * expires integer
   */
  public function setup($data) {

    if (is_object($data[0])) {
      $data = Converter::toArray($data[0]);
    }

    foreach ($data as $k => $d) {
      if (!in_array($k,array("token","expires"))) {
        throw new CatapultApiException("$k is not a valid key for endpoint token");
      }
    }
    $arr = array('token', 'expires');
    foreach ($arr as $a) {
      if (isset($data[$a])) {
        $this->$a = $data[$a];
      }
    }
    // important don't serialize
    // when sending in a Endpoints object
    $this->serialize = FALSE;
  }

  public function __toString() {
    return json_encode(array(
      "token" => $this->token,
      "expires" => $this->expires
    ));
  }
}
