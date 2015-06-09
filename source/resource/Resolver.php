<?php
namespace Catapult;
/**
 * Resolve take a set of arguments
 * and figures out what to 
 * do with it. In other words
 * if we have an id in the parameters
 * call get/1. Conversly no id, call create/1
 * if no data is provided simply exit.
 */
class ResolverResource extends GenericResource {

    public static $keys = array(
        "id",
        "content",
        "number"
    );

    /**
     * finds the main key on a Catapult
     * object
     *
     * @param obj: array or object
     */
    public static function Key($obj) {
      foreach (self::$keys as $k) {
        if (BaseUtilities::prop_or_arr($obj, $k)) {
          return $k;
        }
      }
        
      Throw new \CatapultApiException("This object does not have a valid id, content field"); 
    }

    /**
     * Main function figure out whether
     * to create or get this object.
     * 
     * @param object: Catapult Model Object
     * @param data: EnsuredInput
     */
    public static function Find(&$object, $data)
    {
      $data = Ensure::Input($data);
      $is_str = $data->is_string();

      if ($data->data == null || sizeof($data) == 0)
        return $object;
      /**
       * certain objects cannot
       * be resolved without there parents
       * this is 'only' the case when arity is 1
       * otherwise we follow normal flow
       */
      if ($object->loads->silent && $is_str)
        return $object;
      /**
       * if we have a singleton
       * treat as primary key then
       * invoke create call with
       * in some cases the primary method can be
                   * create in others get
       */
      if ($is_str && !$object->loads->silent)
        if ($object->loads->primary == 'create' || !isset($object->loads->primary))
          return $object->create(array(
            $object->loads->primary => $data
          ));
        else
          return $object->get($data->get());

      $input = Ensure::Input($data);

      $data = $input->get();

      if (in_array("id", array_keys($data)))
        $object->get($data['id']);	
      else
        $object->create($data);
    }


}
final class Resolver extends ResolverResource {}
