<?php

namespace Catapult;

/**
 * types are a set of client side helpers
 * to ease the integration of warnings,
 * exceptions in a application. Unless 
 * specified these should merely serialize into
 * a string with __toString
 *
 * provides:
 * @class DTMF -- Generate valid DTMF's
 * @class TextMessage -- A valid textmessage in length and
 * only containts printable characters
 * @class PhoneNumber -- Valid phone numbers
 * @class CallBackURL -- A URL that is encoded with RFC 3896
 * @class ID -- a Catapult id where the prefix specified its type
 * @class Voice -- A valid voice in Catapult API
 */

/**
 * constructs a legal dtmf where the dtmf is a subset of the valid
 * chars: 
 *
 * Valid chars are '0123456789*#ABCD'
 * DTMFs need to url encoded before being
 * dispatched.
 */
final class DTMF extends Types {
	/**
	 * Initialize the dtmf
	 * as a string
	 * check if all characters 
	 * are valid
	 */
	public static $valid = array(
		"0",
		"1",
		"2",
		"3",
		"4",
		"5",
		"6",
		"8",
		"9",
		"#",
		"A",
		"B",
		"C",
		"D",
		"#",
		"@"
	);

	public function __construct($dtmf='')
	{
		foreach (str_split($dtmf) as $c)
			if (!(in_array($c, self::$valid)))
				throw new \CatapultApiException("Invalid DTMF valid characters: " . implode(',', self::$valid));

		$this->dtmf = $dtmf;
			
	}

	/* DTMF needs to be urlencoded */
	public function __toString()
	{
		return urlencode($this->dtmf);
	}
}
/**
 * Base Models for construction
 * of RESTful properties
 * also provides some helpers
 * to validate input before
 * dispatch
 */
abstract class Types {
	public function Make($args /* polymorphic */)
	{
		return self::__construct($args);
	}
}

/**
 * client side
 * validation of PhoneNumber
 * perform all validation on init
 */
final class PhoneNumber extends Types {
	public function __construct($number)
	{
		$this->number = $number;
	}

	public function Make($args)
	{
		return;
	}

	public function __toString()
	{
		return (string) $this->number;
	}
}

/* Aux functions to make sure text fits */
final class TextMessage extends Types {
	public function __construct($message='')
	{
		$this->message = $message;
	}
	public function __toString()
	{
		return strlen($this->message) >= 160 ? (substr($this->message, 0, 157) . "...") : $this->message;
	}
} 

/**
 * a callback uri object
 * make sure uri fits in
 * compilance with RFC 3986 and
 * is properly encoded on client side
 */
final class Callback extends Types {
	public function __construct($callback='')
	{
		$this->callback = $callback;
	}
	public function __toString()
	{
		return urlencode($this->callback);
	}
}

/**
 * unify timeouts with requests
 * with a microsecond 
 * object. Allow init in seconds
 * or micro
 */
final class Timeout extends Types {
	public function __construct($timeout, $in_seconds=TRUE)
	{
		$this->timeout = $timeout;
		$this->in_seconds = $in_seconds;
	}
	/* PHP only implements toString
	 * requests 'must' convert to int
	 */
	public function __toString()
	{
		$t = ($this->timeout * 1000);
		return (string) ($this->in_seconds ? $t : $this->timeout);
	}
}

/**
 * A page that satisfies Catapult
 * api.
 */
final class Page extends Types {
	public function __construct($page=DEFAULTS::PAGE_SIZE)
	{
		$this->page = $page;
	}

	public function __toString()
	{
		return (string) $this->page;
	}
}

/**
 * A size that satisfies Catapult API
 * exceptions
 */
final class Size extends Types {
	public function __construct($size=DEFAULTS::SIZE)
	{
		if ($size > DEFAULTS::SIZE_MAX)
			Throw new \CatapultApiException("Size too large");

		if ($size < DEFAULTS::SIZE_MIN)
			Throw new \CatapultApiException("Size too small");
	}
	
	public function __toString()
	{
		return (string) ($this->size);		
	}
}

/**
 * represent a catipult
 * style date
 * => 2014-11-08T18:54:30Z
 */
final class Date_ extends Types {
	/* datetime
	 * or unix stamp
	 */
	public function __construct($datetime)
	{
		if (is_int($datetime)) {
			$dt = new DateTime();	
			$dt->setTimestamp($datetime);
		}else {
			$dt = $datetime;
		}

		$this->date = $dt;
	}
	public function __toString()
	{
		return $dt->format("Y-M-DTH:I:SZ");	
	}
}

/**
 * Represent a Catapult
 * type id. Where id 
 * must be an integer and string seperated
 * by a dash. Like so:
 * c-5o5kfc5hzmyshrpkpjnxzjy
 * first letter representing the entity
 * the other being the unique
 */
final class Id extends Types {
	public function __construct($id)
	{
		$this->id = $id;
	}

	public function __toString()
	{
		return (string) $this->id;
	}
}

/**
 * A catapult audio tag. 
 */
final class Tag extends Types {
	public function __construct($str)
	{
		$this->str = $str;
	}

	public function __toString()
	{
		return (string) $this->str;
	}

}

/**
 * A class for the listed
 * voices in Catapult
 * list of available voices
 * defined here: https://catapult.inetwork.com/docs/api-docs/calls/
 * 
 * 
 * TODO: merge voice with gender to  
 * prevent exception.
 */
final class Voice extends Types {
	public static $available_voices = array(
		"Jorge",
		"Kate",
		"Susan",
		"Julie",
		"Dave",
		"Paul",
		"Bridget",
		"Violeta",
		"Jolie",
		"Bernard",
		"Katrin",
		"Stefan",
		"Paola",
		"Luca",
	);
	public function __construct($voice)
	{
		if (!(in_array($voice, self::$available_voices)))
			throw new \CatapultApiException("Voice unrecognized");


		$this->voice = $voice;
	}

	public function __toString()
	{
		return (string) $this->voice;
	}

}

/**
 * Represent a single
 * option in the api.
 * namespace for k,v
 * based commands that
 * control flow of all
 * example: \Catipult\Option("mute", TRUE);
 */
final class Option extends Types {
	public function __construct($key, $val)
	{
		
	}

	public function __toString()
	{

	}
}

/**
 * A one-to-one phone number object
 * where the object should provide
 * from => Catapult\PhoneNumber
 * to => Catapult\PhoneNumber
 */
final class PhoneCombo extends Types {
        public function Make(PhoneNumber $sender, PhoneNumber $receiver)
        {
                   return array(
                        "from" => (string) $sender,
                        "to" => (string) $receiver
                   );
        }
}

/**
 * Merge a finite amount
 * of calls into one structure
 * accepts:
 *  **args => (call,call1, .. calln)
 */
final class CallCombo extends Types {
	public function Make($args /* polymorphic */)
	{
		$calls = func_get_args();

		$call_ids = array();

		foreach ($calls as $call) {

			$call_ids[] = $call->id;
		}

		return $call_ids;
	}
}

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
	
	/* Initial construct
	 * of parameters
	 *
         * @param initial -> [Array | DataPacket]
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
         * @param function -> name of value to set
	 * @param args -> arg to eval
	 */
	public function __call($function, $args /* polymorphic */) 
	{
		if ($function == "get")	
			return;
		
		if (!(isset($args[0])))
			throw new \CatapultApiException("Parameter must be passed to: " . __CLASS__);

		$cmd = substr($function, 0, 3);

		if (!($cmd  == "get" || $cmd == "set"))
			throw new \CatapultApiException("Unknown command in parameters");

		$key = preg_replace("/set|get/", "", $function);
		$key = strtolower(substr($key, 0, 1)) . substr($key, 1, strlen($key));

		if ($cmd == "get")
			if (!(in_array($key, array_keys($this->data))))
				throw new \CatapultApiException("Key not set in parameters");
			else
				return $this->$data[$key];


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

/** 
 * small logic
 * around states
 */
final class States extends Types {
       public function Make($state)
       {
              return array("state" => strtolower($state));
       }
}

/**
 * makes sure the url
 * is an actual
 * media url before dispatch
 */
final class MediaURL extends Types {
	public function __construct($media)
	{
		$this->url = $media;		
	}

	public function __toString()
	{
		return $this->url;
	}
}

/**
 * Minimal filehandler
 * for media types
 */
final class FileHandler extends Types {
	public function save($as=null, $contents)
	{
		$this->as = $as;

		return file_put_contents(realpath($as) . $as, $contents);
	}	
	public function read($filename)
	{
		if (!(is_file(realpath($filename))))
			throw new \CatipultApiException("File does not exist");

		return file_get_contents(realpath($filename));
	}

	public function __toString()
	{
		return (string) $this->as;
	}
}

?>
