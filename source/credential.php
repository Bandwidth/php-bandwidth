<?php

namespace Catapult;

/* Get and set the credential in the JSON file
 * if we cant find credentials.json
 * @object Credentials can also
 * be loaded by associative an array or 
 * from the envorinment variables
 */
final class Credentials {

	private $secure = array("BANDWIDTH_API_SECRET");

	/* All string parameters
	 * if provided use these in place of json credentials
	 */
	public function __construct($user_token='',
				    $api_token='',
				    $api_secret='') {
	}

	/* get a key or all the config in the config */
	public function get($key=null)
	{
		// demo settings will
		// be relative to source
		return $key == null ? json_decode(file_get_contents(__DIR__ . "/credentials.json")) :
				      $this->getVal($key);
	}

	/* pluck a value */
	protected function getVal($key, $show=TRUE)
	{
		$content = json_decode(file_get_contents(__DIR__ . "/credentials.json"));

		return is_array($content->{$key}) ? implode(",", $content->{$key}) : $content->{$key};
	}

        public function getNumber($idx)
	{
		return $content->BANDWIDTH_VALID_NUMBERS[$idx];
	}

	/* set a value */	
	public function set($key, $val)
	{
		$a = $this->get();
		$a[$key] = $val;
	}
}
?>
