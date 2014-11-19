<?php

namespace Catapult;

/**
 * Object based credentials
 * object. Use in place of JSON
 * file if needed. Provide
 * seamless crossover from json object
 */
final class CredentialsUser {
	/* the order we normally get arguments in */
	public static $order = array(
		API::API_BANDWIDTH_USER_ID,
		API::API_BANDWIDTH_TOKEN,
		API::API_BANDWIDTH_SECRET,
		API::API_BANDWIDTH_VALID_NUMBERS
	);

	/**
         * Accept a connection with connections
	 * without a JSON object. This can
	 * be used alternatively.
	 * ex: $cred = new Catapult\Credentials("USER_ID","API_KEY","API_SECRET"); 
         * this will initilaize CredentialsUser as a property of Credentials
	 */
	public function __construct(/* polymorphic accept any arg with 'BANDWIDTH' prefix */)
	{
		$cnt = 0;
		$args = func_get_args();

		foreach ($args as $arg) {
			$k = self::$order[$cnt];
			$this->$k = $arg;

			$cnt ++;
		}
	}
}

/**
 * Get and set the credential in the JSON file
 * if we cant find credentials.json
 * @object Credentials can also
 * be loaded by associative an array or 
 * from the envorinment variables
 */
final class Credentials {

	private $secure = array(
		API::API_BANDWIDTH_SECRET,
		API::API_BANDWIDTH_TOKEN
	);

	public static $credentials_opts = array(
		"path" => "/credentials.json"
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
				    $api_secret='') 
	{ 
		/* only consider parameter init if all provided */
		if ($user_token && $api_token && $api_secret)
			$this->credentials = new CredentialsUser($user_token, $api_token, $api_secret);
	} 


	/* get a key or all the config in the config */
	public function get($key=null)
	{

		if ($key == null)
 		    return json_decode(file_get_contents(__DIR__ . "/credentials.json"));
		      
		      
		return $this->getVal($key);
	}

	/**
         * Returns the area we've
	 * initialized the credentials
	 * object with. Possibilities
         * @return
	 * \stdClass [JSON Object]
	 * \stdClass [CredentialsUser]
         *
	 */
	protected function getParamInstance()
	{ }


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
		if (!($this->credentials instanceof CredentialsUser))
			$content = json_decode(file_get_contents(__DIR__ . "/credentials.json"));
		else
			$content = $this->credentials;

		if (is_array($content->{$key}))
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
