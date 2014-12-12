<?php
namespace Catapult;

function asserte($bool)
{
	$b = assert($bool);
	if (!$b) throw new \CatipultApiException("Assertion failed");
}

function is_multidimensional($array)
{
    return true;
    $rv = array_filter($array,'is_array');
    if(count($rv)>0) return true;

    return false;
}

function camelcase($array)
{
	$c = "";

	foreach ($array as $a)	
		$c .= ucwords($a);

	return $c;
}

/**
 * Base functions
 * for dealing with
 * responses, headers
 * content and internal type
 * handling
 */
class BaseUtilities { 
	public static function find($headers, $term)
	{
		if (!(isset($headers[$term])))
			throw new \CatapultApiException("No header found as $term:");

		return $headers[$term];
	}
}

/**
 * Helpers for the response
 * provide convinience functions
 * to search headers, body, code
 * for needed information
 * locator should check
 * Location:
 * for a url
 */
class Locator extends BaseUtilities {
	/* Specialization for
	 * location: 
	 * header. 
	 * either return the full url
	 * or qualified id which is found
         * as the last directory seperated
         * entity
	 * @param $headers -> string based header string
 	 */
	public static function find($headers,$id=true)
	{
		$header = parent::find($headers, "Location");

		if ($id) {
			$match = array();

			$pieces = explode("/", $header);

			return str_replace("\r", "", str_replace("\n", "", $pieces[sizeof($pieces) - 1]));
		}
		
		return $header;
	}	
}


/**
 * Given a context and command to the api
 * take out any unneeded information
 * before passing it back for a request
 * additionally make sure all legal parameters
 * have been passed
 */
class Prepare extends BaseUtilities {
	/* function Input/1 
	 * should either throw
	 * an error or erase
	 * unneeded arguments
	 * this will vary depending on the
	 * commands strictness
         * @param context -> context
	 * @param data -> data
	 * @param subcotext -> call
	 * @param strict -> validate parameters
	 */
	public function Input($context, $data, $subcontext="all", $strict=FALSE)
	{
		$valid = $context::$valid_opts;

	}

	public function Output($context, $data, $subcontext="all", $strict=FALSE)
	{
		return $this->Input($context, $data, $subcontext, $strict);
	}

}

final class Converter extends BaseUtilities {
	/* in some cases we need to
	 * convert a flat json
	 * object into its array
	 * form. This prevents
	 * extra overhead
	 * in later stages
	 *
	 * @param json: one layer json object
	 */
	public function ToArray($json)
	{
		return get_object_vars($json);
	}
}

/* Provided a set of keywords 
 * take them out of the array
 * this is for events when they come
 * with 'callId', or 'conferenceId'
 * keyword we need to take out the keyword
 * afterwards lowercase the key
 */
final class Cleaner extends BaseUtilities {
	public static $keywords = array(
		"call", "conference", "message"
	);
	/* omits the keyword from
	 * the provided dataset
	 * where the dataset is a single
	 * dimensional array. New keywords without
         * are undercased
	 * @param data 
	 */
	public function Omit($data)
	{
		foreach ($data as $k => $d) {
			foreach (self::$keywords as $key) {
				if (preg_match("/^$key.*$/", $k, $m)) {
					$nk = strtolower(preg_replace("/$key/", "", $k));
					$data[$nk] = $d;
					unset($data[$k]);
				}
			}
		}
				
		return $data;
	}
}

?>
