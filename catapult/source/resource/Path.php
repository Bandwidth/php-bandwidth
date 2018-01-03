<?php
namespace Catapult;
/**
 * Path resource will build paths
 * for objects and figure out which
 * ids they need in their paths.
 * usage follows:
 *
 * PathResource::Make($object)
 *  
 */
class PathResource extends BaseResource {


    /**
     * constructor
     * should allow basic PathResource
     * which would structure based on key value
     * 
     * @param object: Catapult Model
     * @param data: Ensured Data
     */
    public function __construct(&$object, $data) {
      if (!isset($object->hasPath) || !$object->hasPath) {
        $object->path = "";
        foreach ($data as $k => $d) {
          if ($d == "") {
            $object->path .= "/$k";
          } else {
            $object->path .= "$k/$d";
          }
        }
      }
    }

  
    /**
     * path should be build once
     * the object has been
     * initialized with data
     * to build a path we use
     * each dependancy, if has id in data
     * add after path and also set in 
     * object. 
     *
     * result:
     * depend1/depend1id/path
     *
     * @param object: Catapult Model Object
     * @param data: EnsureResource input or Array
     */
    public static function Make(&$object, &$data=null) {
      $path = "";
      foreach($object->depends->terms as $dep) {
        if ($dep->plural) {
          $path .= TitleUtility::toPlural($dep->term) . "/";
        } else {
          $path .= $dep->term . "/";
        }
        /** represent a term in singular form **/
        $term = TitleUtility::toSingular($dep->term) . "Id";
        if (is_array($data) && array_key_exists($term, $data)) {
          $depid = $data[$term];
          $path .= $depid . "/"; 
          $object->{$dep->term} = $depid; 
           
          unset($data[$term]);

          /** only set when we're given data **/
          $object->hasPath = true;
        }
      }

      $object->path = $path . $object->path;
    }
}
