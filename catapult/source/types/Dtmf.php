<?php
/**
 * @type DTMF
 * constructs a legal dtmf where the dtmf is a subset of the valid
 * chars: 
 *
 * Valid chars are '0123456789*#ABCD'
 * DTMFs need to url encoded before being
 * dispatched.
 */
 
namespace Catapult;
final class DTMF extends Types {
    /**
     * Initialize the dtmf
     * as a string
     * check if all characters 
     * are valid
     */
    public static $valid = array(
      "0",
      "1",
      "2",
      "3",
      "4",
      "5",
      "6",
      "8",
      "9",
      "#",
      "A",
      "B",
      "C",
      "D",
      "#",
      "@"
    );

    public function __construct($dtmf='')
    {
      foreach (str_split($dtmf) as $c) {
        if (!(in_array($c, self::$valid))) {
          throw new \CatapultApiException("Invalid DTMF valid characters: " . implode(',', self::$valid));
        }
      }

      $this->dtmf = $dtmf;
        
    }

    /* DTMF needs to be urlencoded */
    public function __toString()
    {
      return urlencode($this->dtmf);
    }
}
