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

	public static $credentials_opts = array(
		"path" => __DIR__ . "/credentials.json"
	);

	private $credentials = array(
		"USER_ID" => "",
		"API_SECRET" => "",
		"API_TOKEN" => ""
	);

	/**
	 * All string parameters
	 * if provided use these in place of json credentials
	 *
	 * @param user_token: Bandwidth user id
	 * @param api_token: API token
	 * @param api_secret: API secret
	 */
	public function __construct($user_token='',
				    $api_token='',
				    $api_secret='') { } 


	/* get a key or all the config in the config */
	public function get($key=null)
	{

		if ($key == null)
 		    return json_decode(file_get_contents(__DIR__ . "/credentials.json")) :
		      
		      
		return $this->getVal($key);
	}


	/**
	 * Gets either a singular
	 * or array based value by
	 * key. Where all keys are 
	 * elements in the credentials.json
	 * file.
	 *
	 * @param key: key for element
	 * @param show: omit with asterisks
	 */
	protected function getVal($key, $show=TRUE)
	{
		$content = json_decode(file_get_contents(__DIR__ . "/credentials.json"));

		if (is_array($content->{$key})
			return implode(",", $content->{$key});
		
		return $content->{$key};
	}

	/**
	 * Gets a number from the provided
	 * set in credentials.json. This
	 * will not have any affect towards
	 * the PhoneNumber service.
	 * 
	 * @param idx: index of number
	 */
        public function getNumber($idx)
	{
		return $content->BANDWIDTH_VALID_NUMBERS[$idx];
	}

	/**
	 * Set a value at runtime
	 *
	 * @param key: key to set
	 * @param val: value
	 */
	public function set($key, $val)
	{
		$a = $this->get();
		$a[$key] = $val;
	}
}
?>
