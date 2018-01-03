<?php
/**
 * @type Callback
 *
 * Catapult callbacks
 */
namespace Catapult;
final class Callback extends Types {
    public function __construct($callback='')
    {
      if (!(filter_var($callback, FILTER_VALIDATE_URL)))
        Throw new \CatapultApiException("Callback is not a valid URL..");

      $this->callback = $callback;
    }

    /** todo add RFC3986 encoding only on query string. **/
    public function __toString()
    {
      return $this->callback;
    }
}
