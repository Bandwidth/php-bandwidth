<?php
namespace Catapult;
/**
 * Forms a new object by both modifiying
 * new object given
 * context by first
 * passing it data to its internal method
 * objects MUST have a
 * set_up, set or setup method
 * 'Borrowed' from Python version
 * this should also keep things
 * neat by switching the 'current'
 * objects state
 */
class Constructor {

    /**
     * Pass two objects, the current and new
     * current should be a reference and determine
     * its new properties. New object
     * should be made seperate
     */
    public static function Make(&$object, $data=array(), $reload=false)
    {
      foreach ($data as $k => $d) {
        $object->$k = $d;
        $checked = true;
       }

       if (!$checked) Throw new \CatapultApiException(EXCEPTIONS::EXCEPTION_OBJECT_DATA . $object->__CLASS__);	

        /* get the full
         * properties of the
         * object incase we havent
         */

        if (isset($object->schema)) {
          foreach ($object->schema->needs as $field) {
            if (isset($object->schema->{$field}) && (!($object->schema->{$field} != null || isset($object->schema->{$field})))) {
              $object = $object->get($object->id);
            }
           }
         }

        /** this assumes the object knows its id **/
        if ($reload) {
          $object = $object->get();
        }

        return $object;
    }

    /**
    * IF we've found setup, set_up or set
    * set the check as true
    * otherwise don't call
    * @param object: Resource Object
    * @param t:  T
    * @param d -> D
    */
    public function check($object,$t,&$d)
    {
      $this->checked = true;

      return $object;
    }

   /** 
    * Constructor's find will merely
    * check if this class is available
    * if it return. Otherwise throw warning
    * 
    * @param class: Catapult namespace class
    */
    public function find($class)
    {
      if (!get_class($class)) {
        // todo 
        // this is not needed. throw a warning or error
      }

      return $class;
    }
}
