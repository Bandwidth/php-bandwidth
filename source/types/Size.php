<?php
/**
 * @type Size
 *
 * A size that satisfies Catapult API
 * exceptions
 */

namespace Catapult;
final class Size extends Types {
    public function __construct($size=DEFAULTS::SIZE)
    {
      if ($size > DEFAULTS::SIZE_MAX) {
        Throw new \CatapultApiException("Size too large. Size was: " . $size);
      }

      if ($size < DEFAULTS::SIZE_MIN) {
        Throw new \CatapultApiException("Size too small. Size was: " . $size);
      }

      $this->size = $size;
    }

    public function __toString()
    {
      return (string) ($this->size);		
    }
}
