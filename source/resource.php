<?php
namespace Catapult;

/**
 * Generate a resource location
 * for Catapult. Either initialize
 * as object or accessible by one time
 * command: Make/2
 */

class URIResource extends BaseResource {
	/**
	 * Object form of URIResource
	 * should allow to
	 * same functionality
	 * as Make/2 when casted to
	 * string
	 * @param $path -> partial path
	 * @param $extras -> extra endpoints
	 */
	public function __construct($path, $extras=array())
	{
		$this->path = $path;
		$this->extras = $extras;
	}

	/**
	 * Straight forward
	 * making of string
	 * seperator can 
	 * be changed for queriying
	 * @param $path -> partial path
	 * @param $extras -> extra endpoints
	 */
	public function Make($path, $extras=array(), $sep="/")
	{
		$ext = "";

		if (sizeof($extras) > 0)
			foreach ($extras as $e)
				$ext .= (string) $e . $sep;


		/* dont seperate if no extras were provided */

		if ($ext != "")
			return $path . $sep . $ext;
		

		return $path;
	}

	/**
	 * Look in make for more
	 * URIResource should
	 * only allow its contents to be fetched
	 * through this
	 */
	public function __toString()
	{
		return (string) self::Make($this->path, $this->extras);
	}
}

/**
 * Represent object in string
 * form. Where form must be compilant
 * of:
 * (CLASS)(op1,op2..op)
 */
class StringifyResource extends BaseResource {
	public function __construct($class, $options)
	{
		$this->class = $class;
		$this->options = $options;
	}

	/**
	 * Make the string
	 * directly
	 * @param $class -> __class__
	 * @param $extras -> list of parameters to encode
	 */
	public function Make($class, $extras)
	{
		return "";
	}

	public function __toString()
	{
		return self::Make($this->class, $this->options);
	}
}

/**
 * Represent a stateful
 * transfer as an object
 * before serializing to either
 * JSON or XML
 */
class RequestResource extends BaseResource {
	private $dispatched;

	/**
	 * A request resource
	 * works like DataPacket
 	 * only should be initialized 
	 * by clients and not models
	 * @param data -> DataPacketCollection | Array | DataPacket
 	 */
	public function __construct(DataPacketCollection $data)
	{}
}

/**
 * Response version
 * to the request
 * resource. Take
 * a raw string and represent
 * as one of listed interop formats
 */
class ResponseResource extends BaseResource {
	private $status;

	/**
	 * Represent a raw
	 * object as a response
	 * check whether the response
	 * was a success or has
	 * been ignored due to some non 
         * HTTP reason
	 *
  	 * Since the response is already
	 * parsed and distinguished in type
         * ahead of time, merely check
         * the status codes
  	 * 
         * @param $raw -> string of response
 	 */	
	public function __construct($raw)
	{}
}

interface SchemaResource {}

/**
 * Lexical support
 * for options in api
 * Each resource must be initialized
 * with {Key}, {Context}, {Required}
 */
class OptionResource {

	/* make a option for a given schema
	 * @param key -> key
	 * @param context -> call it is used in
	 * @param required -> wehther the argument is required
	 */
	public function make($key=NULL, $context=NULL, $required=NULL)
	{
		/* any logic
		 * needed behind
	 	 * parameter filtering
		 * here..
	 	 */

		$schema_piece = array();

		$schema_piece[$key] = $key;
		$schema_piece[$context] =  $context;
		$schema_piece[$required] = $required;

		if (!is_array(SchemaResource::${context}))
			SchemaResource::${context} = array();

		/* add to the global
	         * schema for
	         * this context
		 */

		if (in_array(SchemaResource::${context}))
			throw new \CatapultApiException("This schema already has this key");


		return SchemaResource::${context}[] = $schema_piece;
	}
}


/**
 * A generic resource type
 * extends ListResource
 * and provide basic schema
 */ 
class GenericResource extends ListResource { 

	public $lastUpdate;

	public static $valid_opts = array(
	);

	/**
	 * return a file descriptor
	 * as the pointer
	 * to the client
	 * if not abstracted
	 */
	public function __construct()
	{
		$this->client = Client::get(__DIR__);
	}

	/**
	 * accomodate for camelcase vs underscore
	 * @param function a underscored function
	 * @return this->camelizedContext
	 * ex: $call->listCalls -> $call->list_calls
	 *
	 */
	public function __call($function, $args)
	{
		$glue = "";
		$m = array();
		preg_match("/([a-z]+)[A-Z]/", $function, $m);
		
		if (sizeof($m) > 0)	
			$glue .= $m[1];	

		$m = array();
		preg_match_all("/([A-Z][a-z]+)+/", $function, $m);		

		if (sizeof($m) > 0) {
			foreach ($m[1] as $m1) {
				$glue .= "_" . strtolower($m1);
			}
		}


		if (method_exists($this, $glue))
			return $this->{$glue}($args);
		else
			return NULL;
	}

	/**
	 * default string representation
	 * of object.
	 *
	 * @return string that represents the object
	 */
	public function __toString()
	{
		return __CLASS__ . "(" . "state=" . $this->id . ")";
	}


        /**
	 * Default getter for key
         * this should merely try 
         * to fetch the key as a public property
         *
         * @param key -> key
         */
	public function get($key)
	{
                if (!(in_array($key, $this->fields) || isset($this->${key})))
                       throw new \CatapultApiException(EXCEPTIONS::FIELD_HAS_NOT_BEEN_SET . __CLASS__);

		return $this->${key};		
	}

	/**
	 * check will retrieve latest info
         * of the entity then compare a field to
         * a given value
         * ex: $call->check('state', 'active') => TRUE
         * 
         * @param k: key to check
         * @param v: value to compare against
         */ 
	public function check($k,$v)
	{
		$this->get($this->id);

		if ($this->$k == $v)
			return TRUE;

		return FALSE;
	}

	/**
	 * load the object with static properties
	 * usually done client side -- or by collections
         * already holding information on an object
         * 
         * @param props -> set of properties to load
	 */
	public function load($props)
	{
		foreach ($props as $k => $prop)
			$this->{$k} = $prop;
	}

	/**
	 * reload the object with updated information   
         * this will call the member's get function
         * and check for changes.
	 */
	public function reload()
	{
		return $this->get($this->id);
	}

        /**
	 * Strict set up where
         * all fields must be coherent
         * with the object's schema. 
         *
         *
         * @param data -> set of fields, values
         */
	public function set_up($data)
	{
		foreach ($data as $k => $d) 
			if (in_array($k, $this->fields))
				$this->${k} = $d;
			else
				throw new \CatapultApiException(EXCEPTIONS::KEY_NOT_FOUND_FOR . __CLASS__);
	}
}

/**
 * EnsureResource called before dispatching of 
 * datapackets. Make sure all parameters
 * are of correct type. where types can be: 
 *
 * @object Parameter, 
 * @object DataPacket
 * associative array
 * 
 */
class EnsureResource extends BaseResource {
	/**
	 * Verify the input is of the correct
	 * type. Additionally delegate to Collection if needed
         *
	 * @param $data -> [array | multidimensional array]
	 */
	public function Input(&$data)
	{
		if ($data instanceof Parameters || $data instanceof CollectionObject)
			return new DataPacket($data);

		if (isset($data[0]) && is_array($data) && is_multidimensional($data) && is_array($data[0]) && sizeof($data) == 1)
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
	public function Output($data)
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
	public function Strict(&$data, $key)
	{
		if ($data instanceof DataPacket)
			$data = $data->get();

		if (!(in_array($key, array_keys($data))))
			Throw New \CatapultApiException("You must add $key to call this function");
	}
}

final class Ensure extends EnsureResource {}

/**
 * return a 
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
	public function Make(&$object, $data=array())
	{
                foreach ($data as $k => $d) {
                       $object->$k = $d;
                       $this->checked = true;
                }
			
		if (!$this->checked)
			Throw new \CatapultApiException(EXCEPTIONS::EXCEPTION_OBJECT_DATA . $object->__CLASS__);	

		/* get the full
	         * properties of the
	         * object incase we havent
	         */

		foreach ($object::$needs as $field)
			if (isset($object->{$field}) && (!($object->{$field} != null || isset($object->{$field})))) {
				$object = $object->get($object->id);
			}

		return $object;
	}

	/**
	 * IF we've found setup, set_up or set
	 * set the check as true
	 * otherwise don't call
	 * @param $object -> Resource Object
	 * @param $t -> T
	 * @param $d -> D
	 */
	public function check($object,$t,&$d)
	{

		$this->checked = true;

		return $object;
	}

	/* Constructor's find will merely
	 * check if this class is available
	 * if it return. Otherwise throw warning
	 * 
	 * @param class: Catapult namespace class
	 */
	public function find($class)
	{
		return $class;
	}
}

/**
 * Resolve take a set of arguments
 * and figures out what to 
 * do with it. In other words
 * if we have an id in the parameters
 * call get/1. Conversly no id, call create/1
 * if no data is provided simply exit.
 */
class ResolverResource extends GenericResource {
	/* object version */
	public function __construct($object, $data)
	{
	}

	public function Find(&$object, $data)
	{
		/* if the data is null or its a quiet load return */
		if ($data == null)
			return $object;

		/**
		 * if we have a singleton
		 * treat as primary key then
	 	 * invoke create call with
		 * in some cases the primary method can be
                 * create in others get
	 	 */
		if (is_string($data))
			if ($object->primary_method == 'create' || !isset($object->primary_method))
				return $object->create(array(
					$object->primary_init => $data
				));
			else
				return $object->get($data);

		$input = Ensure::Input($data);

		$data = $input->get();

		if (in_array("id", array_keys($data)))
			$object->get($data['id']);	
		else
			$object->create($data);
	}


}

final class Resolver extends ResolverResource {}
class BaseResource {}
class ListResource extends BaseResource {} 
final class CreateResource extends BaseResource {}

?>
