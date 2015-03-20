<?php
/**
 * @type SIPRealm
 *
 * Validates an SIP realm
 */

namespace Catapult;

final class SIPRealm extends Types {
  public function __construct($siprealm) {
    $this->siprealm = $siprealm;
  }

  /**
   * validation should check
   * the four point rule
   *
   * {domain_name}.{application}.{resource_provider}.{tld}
   */
  public function isValid() {
    if (preg_match("/(.*)\.(.*)\.(.*)\.(.*)/", $this->siprealm)) {
      return true;
    }
    return false;
  }

  public function __toString() {
    return $this->siprealm;
  }
}
