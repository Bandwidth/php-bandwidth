<?php
/**
 * @class BaMLAttribute
 *
 * provide a key value attribute object
 * for BaML objects.
 */
namespace Catapult;
final class BaMLAttribute extends BaMLAssert {
    private $key;
    private $value;
    /** baML attributes do not set valid **/
    /** take a tuple as a key, value pair **/
    public function __construct($text, $val) {
      $this->key = $text;
      $this->value = $val;
    }
    public function getValue() {
      return $this->value;
    }
    public function getKey() {
      return $this->key;
    }
}
