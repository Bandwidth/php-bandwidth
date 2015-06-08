<?php

/**
 * @class BaMLContainer
 *
 * base wrapper around BaMLVerbs
 * these should be used 
 * parsing or generation.  
 */
namespace Catapult;

class BaMLContainer extends BaMLGeneric {
      public static $valid = array(
          "Request",
          "Response"
      );
      /** full set of root level verbs **/
      public $verbs = array(
      );
      /** always found on root level: 0 **/
      public $level = 0;
      /** either Request or Response **/
      public $type = "";
      public function __construct($type="Request") {
        if (!(strtolower($type) == "request" || strtolower($type) == "response"))
            throw new \CatapultApiException("BaML container must be either Request or Response");
        $this->type = $type;
      }
      public function getType() {
        return $this->type;
      }
      public function setType($type) {
        if (!(strtolower($type) == "request" || strtolower($type) == "response"))
          throw new \CatapultApiException("BaML container must be either Request or Response");
        $this->type = $type;
      }
}
