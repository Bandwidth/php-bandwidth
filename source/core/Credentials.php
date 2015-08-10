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
      API::API_BANDWIDTH_APPLICATION_ID,
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
      $found = array();

      foreach ($args as $arg) {
        $found[] = $k = self::$order[$cnt];
        $this->$k = $arg;

        $cnt ++;
      }

      foreach (self::$order as $key) {
        if (!(in_array($key, $found)))
          $this->$key = "";

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

    /** 
     * credentials should initially be set to 'credentials.json' 
     * which will be found in the parent directory 
     *
     */
    public static $credentials_opts = array(
      "file" => "credentials.json"
     );

    /** set the credentials incase we need JSON **/
    private $credentials = null;

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
      $file = self::$credentials_opts['file'];

      /* only consider parameter init if all provided */
      if (isset(self::$credentials_opts['path'])) {
        /** do not load path twice **/
        if (!preg_match("/credentials\.json/", self::$credentials_opts['path'])) {
          self::$credentials_opts['path'] = self::$credentials_opts['path'] . DIRECTORY_SEPARATOR . self::$credentials_opts['file'];
        }
      } else {
        self::$credentials_opts['path'] = realpath(getcwd()) . DIRECTORY_SEPARATOR . self::$credentials_opts['file'];
      }

      if ($user_token && $api_token && $api_secret) {
        $this->credentials = new CredentialsUser($user_token, $api_token, $api_secret);
      } else {
        if (!is_file(self::$credentials_opts['path'])) {
          throw new \CatapultApiException("$file was not found in directory " . self::$credentials_opts['path']);
        }
        $this->credentials = file_get_contents(self::$credentials_opts['path']);
      }
    } 


    /* get a key or all the config in the config */
    public function get($key=null)
    {

      if ($key == null) {
        return json_decode(file_get_contents(self::$credentials_opts['path']));
      }

      return $this->getVal($key);
    }

    /**
     * Returns the area we've
     * initialized the credentials
     * object with. Possibilities
     *
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
     * @param key: key for element
     * @param show: omit with asterisks
     */
    protected function getVal($key, $show=TRUE)
    {
      if (!($this->credentials instanceof CredentialsUser)) {
        $content = json_decode(file_get_contents(self::$credentials_opts['path']));
      } else {
        $content = $this->credentials;
      }

      if (!array_key_exists($key, get_object_vars($content))) {
        return false;
      }

      if (is_array($content->{$key})) {
        return implode(",", $content->{$key});
      }
            
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
     * Shortcut. Get the first app
     * id. Usage
     *
     * @param idx: index of app
     */
    public function getApplicationId($idx=0)
    {
      return $content->BANDWIDTH_APPLICATION_ID;
    }

    /**
     * Set a value at runtime
     *
     * @param key: key to set
     * @param val: value
     */
    public function set($key, $val)
    {
      $this->credentials->$key = $val;
    }

    /**
     * Sets the path of credentials.json
     *
     * @param path: path
     */
    public static function setPath($path)
    {
      $rf = realpath($path);
      if (is_file($rf))
        throw new \CatapultApiException("Provided folder does not exist");  

      self::$credentials_opts['path'] = realpath($path);
    }

    /**
     * Sets the file 
     * to find credentials
     * suitable when the term credentials is not
     * being used for the credentials file
     *
     * @param file: file
     */
    public static function setFile($file)
    {
      $rf = realpath($file);
      if (is_file($rf))
        throw new \CatapultApiException("File provided does not exist..");  

      self::$credentials_opts['file'] = realpath($file);
    }

}
