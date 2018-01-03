<?php
namespace Catapult;

/**
 * Base functions
 * for dealing with
 * responses, headers
 * content and internal type
 * handling
 */
class BaseUtilities { 
      /**
       * check for multidimensional array, or objects.
       *
       * perform a shallow inspection
       * traversing each object/array, would be too slow
       * @param context: array or object
       */
      public static function is_multidimensional($context, $shallow=true)
      {
        if (!(sizeof($context) > 0)) {
             return FALSE;
        }

        if ($shallow && (is_array($context[0]) || is_object($context[0]))) {
             return TRUE;
        }

        return false;
      }

      /**
       * camelcase the provided
       * text. same as default
       * if already camelized
       */
      public static function camelcase()
      {
        $c = "";

        foreach ($array as $a)	
            $c .= ucwords($a);

        return $c;

      }

      /**
       * find a particular item 
       * in a parsed headers array
       * @param assoc header array
       * @param term term to get
       */
      public static function find($headers, $term)
      {
        if (!(isset($headers[$term]))) {
          throw new \CatapultApiException("No header found as $term:");
        }

        return $headers[$term];
      }

      /**
       * internal:
       * are the two Catapult types
       * the same
       *
       * @param var1: array or object
       * @param var2: array or object
       */
      public static function same_type(&$var1, &$var2){
          return gettype($var1) === gettype($var2);
      }

      /**
       * perform on an object or array
       * returning its property or index
       * 
       * @param object: array or object
       * @param prop: property
       */
      public static function prop_or_arr($obj, $key) {
        if (is_object($obj) && array_key_exists($key, get_object_vars($obj))) {
            return $obj->$key;
        }

        if (is_array($obj) && isset($obj[$key])) {
            return $obj[$key];
        }

        return false;
      }

      /**
       * make an assoc array
       * from a func_get_args/1
       * passing, must have 
       * key array. Follows numerical order
       */

      public static function assoc($arr, $keys) {
        $out = array();
        foreach ($arr as $cnt => $a) {
            $out[$keys[$cnt]] = $a;
        }

        return $out; 
      }


    /**
     * Checks if two objects
     * are references of each
     * other
     *
     * @param var1: array or object
     * @param var2: array or object
     */
    public static function is_ref(&$var1, &$var2) {
      //If a reference exists, the type IS the same
      if(!self::same_type($var1, $var2)) {
          return false;
      }

      $same = false;

      if(is_array($var1)) {
       do {
           $key = uniqid("is_ref_", true);
       } while(array_key_exists($key, $var1));

       //The two variables differ in content ... They can't be the same
       if(array_key_exists($key, $var2)) {
         return false;
       }

       //The arrays point to the same data if changes are reflected in $var2
       $data = uniqid("is_ref_data_", true);
       $var1[$key] =& $data;
       //There seems to be a modification ...
       if(array_key_exists($key, $var2)) {
          if($var2[$key] === $data) {
               $same = true;
          }
       }

        //Undo our changes ...
        unset($var1[$key]);
      } elseif(is_object($var1)) {
        //The same objects are required to have equal class names ;-)
        if(get_class($var1) !== get_class($var2)) {
           return false;
        }

        $obj1 = array_keys(get_object_vars($var1));
        $obj2 = array_keys(get_object_vars($var2));

        //Look for an unused index in $var1
        do {
         $key = uniqid("is_ref_", true);
        } while(in_array($key, $obj1));

        //The two variables differ in content ... They can't be the same
        if(in_array($key, $obj2)) {
          return false;
        }

        //The arrays point to the same data if changes are reflected in $var2
        $data = uniqid("is_ref_data_", true);
        $var1->$key =& $data;
        //There seems to be a modification ...
        if(isset($var2->$key)) {
          if($var2->$key === $data) {
             $same = true;
          }
        }

          //Undo our changes ...
          unset($var1->$key);
        } elseif (is_resource($var1)) {
          if(get_resource_type($var1) !== get_resource_type($var2)) {
             return false;
          }

          return ((string) $var1) === ((string) $var2);
        } else {
          //Simple variables ...
          if($var1!==$var2) {
            //Data mismatch ... They can't be the same ...
            return false;
          }

          //To check for a reference of a variable with simple type
          //simply store its old value and check against modifications of the second variable ;-)

          do {
            $key = uniqid("is_ref_", true);
          } while($key === $var1);

          $tmp = $var1; //WE NEED A COPY HERE!!!
          $var1 = $key; //Set var1 to the value of $key (copy)
          $same = $var1 === $var2; //Check if $var2 was modified too ...
          $var1 = $tmp; //Undo our changes ...
        }

          return $same;
        }

       /**
        * make a catapult formatted
        * date a native PHP date or unix
        * stamp
        * @param date: Catapult date (2015-01-12T13:49:43Z) format
        * @param std: Standard PHP dates
        */
       public function datefromApi($date, $std = false) {
         $object = \DateTime::createFromFormat(API::API_DATE_FORMAT, $date);
      
          if ($std) {
            return getdate($object->getTimestamp());
          }

          return $object;
        }

       /**
        * date to Catapult
        * format. Catapult\Date 
        * uses this
        ** deprecate Catapult\DateStamp in place of this
        * @param date
        */
      public function dateToApi($date) {
        $object = new \DateTime;

        return $object(API::API_DATE_FORMAT);
      } 
}
