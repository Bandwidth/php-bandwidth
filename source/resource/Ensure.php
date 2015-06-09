<?php
namespace Catapult;

/**
 * EnsureResource called before dispatching of 
 * datapackets. Make sure all parameters
 * are of correct type. where types can be: 
 *
 * @object Parameter, 
 * @object DataPacket
 * @object BaML input
 * @object polymorphic func_get_args
 * 
 * associative array
 * 
 */
class EnsureResource extends BaseResource {
    /**
     * Verify the input is of the correct
     * type. Additionally delegate to Collection if needed
     *
     * @param data: EnsureInput as listed above
     */
    public static function Input($data)
    {

      /** handle a multiple ensure **/
     
      $args = func_get_args();
      if (count($args) > 1) {
          return EnsureResource::InputTwo($args); 
      }

      if ($data instanceof DataPacket || $data instanceof DataPacketCollection)
          return $data;
      /** empty if not set **/
      if (!isset($data) || $data == null)
          return new DataPacket(array());

      if ($data instanceof Parameters)
          return new DataPacket($data->data);

      if (isset($data[0]) && $data[0] instanceof Parameters)
          return new DataPacket($data[0]->data);

      if ($data instanceof CollectionObject || is_string($data) ||
          $data instanceof BaML || $data instanceof BaMLAttribute || $data instanceof BaMLVerb)
      return new DataPacket($data);

      if (isset($data[0]) && isset($data[1]) && !is_array($data[0]) && is_array($data[1])) 
         return new DataPacket(array_merge(array($data[0]), $data[1]));

      if (isset($data[0]) && is_array($data) && BaseUtilities::is_multidimensional($data) && is_array($data[0]) && sizeof($data) == 1)
        return new DataPacket($data[0]);

      if (!(is_array($data) || $data instanceof \stdClass))
        Throw new \CatapultApiException(EXCEPTIONS::WRONG_PARAMETERS);

      if (!($data instanceof DataPacket && is_multidimensional($data)))
        return new DataPacket($data);

      if (!($data instanceof DataPacketCollection))
        return new DataPacketCollection($data);
    }

    /**
     * Output version same thing should verify
     * if its a DataPacket before sending back
     *
     * @param $data -> [array | multidimensional array]
     */
    public static function Output($data)
    {
      return Input($data);
    }

   /**
    * If a parameter is not found in the set
    * throw error. Example:
    *
    * Ensure::Strict($this, "id")
    * @param $data -> set of schema data
    * @param $key -> key that 'NEEDS' to exist
    */
    public static function Strict(&$data, $key)
    {
      if ($data instanceof DataPacket)
        $data = $data->get();

      if (!(in_array($key, array_keys($data))))
        Throw New \CatapultApiException("You must add $key to call this function");
    }

    /**
     * handles a two arity
     * where the split becomes
     *
     * [0] => array 1
     * [1] => array 2
     *
     * Input should not be insured yet..
     */
    public static function InputTwo($args) 
    {
      $one = Ensure::Input($args[0]);
      $two = Ensure::Input($args[1]);

      return array(
        0 => $one,
        1 => $two
      );
    }
}

final class Ensure extends EnsureResource {}
