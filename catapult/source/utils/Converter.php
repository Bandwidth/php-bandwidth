<?php
namespace Catapult;
/**
 * Set of functions to convert 
 * Catapult functions to and from 
 * native PHP objects, arrays, etc
 *
 * TODO: move date based functions from Base
 *
 */
final class Converter extends BaseUtilities {
	/* in some cases we need to
	 * convert a flat json
	 * object into its array
	 * form. This prevents
	 * extra overhead
	 * in later seles
	 *
	 * @param json: one layer json object
	 */
	public static function ToArray($json)
	{
		return get_object_vars($json);
	}
}
