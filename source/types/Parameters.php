<?php
/**
 * @type Parameters
 *
 * Serializes a set of parameters
 * into an object. Can be used throughout
 * the public API
 */
namespace Catapult;
/**
 * convinience function
 * for parameters. Serialize
 * into DataPacket on addition
 * Usage like:
 * $params = new Catapult\Parameters();
 * $params->setParam1("val");
 * $params->setParam2("val");
 */
final class Parameters extends Types {
    
    /** 
     * Initial construct
     * of parameters
     *
     * @param initial: [Array | DataPacket]
     */
    public function __construct($initial=array())	
    {
      $this->data = array();
    }

    /* set the needed
     * parameter as called
     *  
     * must be called with string 'set' 
     * in function name
     * @param function: name of value to set
     * @param args: arg to eval
     */
    public function __call($function, $args /* polymorphic */) 
    {
      if ($function == "get")	{
        return;
      }
      
      if (!(isset($args[0]))) {
        throw new \CatapultApiException("Parameter must be passed to: " . __CLASS__);
      }

      $cmd = substr($function, 0, 3);

      if (!($cmd  == "get" || $cmd == "set")) {
        throw new \CatapultApiException("Unknown command in parameters");
      }

      $key = preg_replace("/set|get/", "", $function);
      $key = strtolower(substr($key, 0, 1)) . substr($key, 1, strlen($key));

      if ($cmd == "get") {
        if (!(in_array($key, array_keys($this->data)))) {
          throw new \CatapultApiException("Key not set in parameters");
        } else {
          return $this->$data[$key];
        }
      }


      /* set the key in parameter */

      $this->data[$key] = $args[0];
    }

    /**
     * Serialize the
     * object into its
     * datapacket reciprocal
     *
     */
    public function serialize()
    {
      $d = new DataPacket($this->data);

      $this->data = array();

      return $d;
    }

    /* stub */
    public function get()
    {
      return $this->serialize();
    }
}
