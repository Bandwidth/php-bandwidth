<?php
namespace Catapult;
/**
 * Helpers for the response
 * provide convinience functions
 * to search headers, body, code
 * for needed information
 * locator should check
 * Location:
 * for a url
 */
class Locator extends BaseUtilities {
   /* Specialization for
    * location: 
    * header. 
    * either return the full url
    * or qualified id which is found
    * as the last directory seperated
    * entity.
    * example:
    *
    * full: term1/term2/{id} => id
    *
    * @param headers: string based header string
    * @param id: return only the id
    */
    public static function find($headers,$id=true)
    {
      $header = parent::find($headers, "Location");

      if ($id) {
        $match = array();
        $pieces = explode("/", $header);
        return str_replace("\r", "", str_replace("\n", "", $pieces[sizeof($pieces) - 1]));
      }

    return $header;
    }	
}
