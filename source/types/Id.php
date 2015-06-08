<?php
/**
 * @type Id
 *
 * Represent a Catapult
 * type id. Where id 
 * must be an integer and string seperated
 * by a dash. Thus:
 * c-5o5kfc5hzmyshrpkpjnxzjy
 *
 * prefix representing the entity
 * the other being the unique. 
 */
 
namespace Catapult;
final class Id extends Types {
     /**
      *
      * A list of known prefixes
      * TODO: Try to make the type
      * switch whenever match with prefix below
      */
    public static $prefixes = array(
      "c",
      "conf",
      "b",
      "m",
      "g",
      "rec",	
      "transc",
      "u"
    );

    /**
     * construct an id object
     * throws on error. If not needed for
     * exception handling use: valid/1
     *
     * @param id: valid Catapult Id
     */
    public function __construct($id)
    {
      if (!(self::valid($id))) {
        throw new \CatapultApiException("Invalid id, used: $id");
      }

      $this->id = $id;
    }

    /**
     * check if the supplied
     * id is valid
     * 
     * @param id: valid Catapult Id
     */
    public function valid($id)
    {
      $valid = FALSE;

      foreach (self::$prefixes as $prefix) {
        $m = array();

        preg_match("/$prefix-.*/", $id, $m);

        if (sizeof($m)) {
          $valid = TRUE;
        }
      }

      return $valid;
    }

    public function __toString()
    {
      return (string) $this->id;
    }
}
