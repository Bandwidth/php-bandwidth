<?php
/**
 * @type Timeout 
 * 
 *
 * unify timeouts with requests
 * with a microsecond 
 * object. Allow init in seconds
 * or micro, output should always
 * be in micro.
 */

namespace Catapult;

final class Timeout extends Types {
    public function __construct($timeout, $in_seconds=TRUE)
    {
      $this->timeout = $timeout;
      $this->in_seconds = $in_seconds;
    }
    /* PHP only implements toString
     * requests 'must' convert to int
     */
    public function __toString()
    {
      $t = ($this->timeout * 1000);
      return (string) ($this->in_seconds ? $t : $this->timeout);
    }
}
