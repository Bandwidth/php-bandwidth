<?php
namespace Catapult;
/**
 * Standard Phone Number Validation. 
 * Numbers need to be in E.164 format
 */
final class PhoneNumber extends Types {
    public function __construct($number)
    {
      $this->number = $number;
    }
    public function perform($warn) 
    {
      $m = array();
      preg_match("/^([0-9\(\)\/\+ \-]*)$/", $this->number, $m);

      if (!sizeof($m) > 0 && $warn) {
        throw new \CatapultApiException("Invalid phone number inputed: " . $number);
      }
      if (!sizeof($m) > 0) {
        return FALSE;
      }

       return TRUE;
    }

    public function isValid()
    {
      return $this->perform(FALSE);
    }

    public function __toString()
    {
      $this->perform(TRUE);

      return (string) $this->number;
    }
}
