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
	public static function Make($path, $extras=array(), $sep="/")
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
