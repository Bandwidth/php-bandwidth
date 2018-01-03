<?php
/**
 * @type Date
 * Catapult formatted
 *
 * represent a catipult
 * style date
 * Example:
 * 2014-11-08T18:54:30Z
 *
 */
namespace Catapult;
final class Date extends Types {
   /** 
    * Input should allow datetime objects
    * or unix stamps.
    */
    public function __construct($datetime)
    {
      if (is_int($datetime)) {
        $dt = new \DateTime();	
        $dt->setTimestamp($datetime);
      } else {
        $dt = $datetime;
      }

      $this->date = $dt;
    }
    public function __toString()
    {
      return $this->date->format(API::API_DATE_FORMAT);	
    }
}
