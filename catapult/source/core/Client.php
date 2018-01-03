<?php
namespace Catapult;


/**
 * RESTful client for Bandwidth. These contain
 * components for all HTTP requests 
 * made with Bandwidth.
 * 
 *
 * @object Client -- A direct interface to get, and reset clients
 * all models, types and generics point to 'only' one client
 * per file descriptor. 
 *
 * @object RESTclient -- HTTP manager for requests
 *
 * NOTE: Client should be accessible to any object
 * also be referenced by it. This should be maintained
 * by function Get/1
 */

final class Client {
   /** client moves to Client **/
   public static $CLIENT;
   /**
    * Construct a client based on Credential object.
    * delegate to env when no args
    *  
    * 1/30/2015 
    * update supports with or without credential
    * object. when none is provided construct based on
    * arguments. Add Credentials object implicitly 
    * 
    * previously:
    * $credetials = new Catapult\Credentials(params..)
    * client($credentials)
    *
    * now:
    * client = new Catapult\Client(params..)
    *
    * @param args: Credential | String
    */
    public function __construct(/** polymorphic **/)
    {
      $args = func_get_args();

      /** only needs 3 parameters **/
      if (isset($args[0]) && is_string($args[0])) 
        $this->make(new Credentials($args[0], $args[1], $args[2]));
        
      /** no parameters means implicit credentials no passing **/
      /** this is when the user has loaded the json credentials **/
      elseif (sizeof($args) == 0) 
        $this->make(new Credentials);

      /** backwards compatability here **/
      elseif ($args[0] instanceof Credentials)
        $this->make($args[0]);
      else
        $this->make($_ENV);
    }

    /**
     * Makes the client out of either the envorinment,
     * Credentials object or associative array
     *
     *
     * @param ctx: Credential | array | Envorinement
     */
    protected function make($ctx)
    {
      // ENV or raw args      
      if (!is_object($ctx)) {
        $this->user_id = $ctx['BANDWIDTH_USER_ID'];
        $this->token = $ctx['BANDWIDTH_API_TOKEN'];
        $this->secret = $ctx['BANDWIDTH_API_SECRET'];
        $this->application_id = $ctx->get('BANDWIDTH_APPLICATION_ID');
      } else {
        $this->user_id = $ctx->get('BANDWIDTH_USER_ID');
        $this->token = $ctx->get('BANDWIDTH_API_TOKEN');
        $this->secret = $ctx->get('BANDWIDTH_API_SECRET');
        $this->application_id = $ctx->get('BANDWIDTH_APPLICATION_ID');
      }

      if (!isset($this->user_id)
         ||!isset($this->token)
         ||!isset($this->secret))
              throw new \CatapultApiException("Credentials were improperly configured");

      $this->started = TRUE;

      return (self::$CLIENT = new RESTClient($this->user_id, array($this->token, $this->secret), $this->application_id));
    }

    /**
     * Return global object 'client'
     * arg can optionally be directory descriptor
     * where a directory is subject to ONE client
     * @param: string [directory descriptor]
     */
    public static function get($descriptor=__FILE__)
    {
      return self::$CLIENT;
    }       

    /**
     * Reset the client
     * @param $client_ -> RESTfulClient
     */
    public function set($client_)
    {
      self::$CLIENT = $client_;
    }
  }

  final class RESTClient {
      /* rudimentary options */
    public static $CURL_OPTS = array(
      CURLOPT_CONNECTTIMEOUT => 10,
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_TIMEOUT        => 60,
      CURLOPT_USERAGENT      => 'catapult-php-sdk.5.0'
    );

    /** media types we need to handle **/
    public static $media_formats = array(
      "audio/wav",
      "audio/mp3"
     );
   
    /** settings for SSL. **/       
    public static $standard_opts = array(
      "endpoint" => null,
      "ssl" => TRUE,
      "ssl_key" => FALSE,
      "verify" =>FALSE
     );

    /**
     * CTor for RESTful
     * client default to definition
     * of endpoint, interop format
     * @param user_id -> Catapult User Id
     * @param app_id -> Primary app id
     * @param auth -> API Credentials
     * @param endpoint -> Catapult endpoint
     */
    public function __construct($user_id='', $auth=array(), $app_id=API::API_DEFAULT_APPLICATION, $endpoint=API::API_ENDPOINT, $interop=API::APPLICATION_JSON)
    {
      if (!empty(self::$standard_opts['endpoint']))
        $this->endpoint = self::$standard_opts['endpoint'];
      else
        $this->endpoint = $endpoint;
      $this->interop = $interop; 
      $this->uid = $user_id;
      $this->application_id = $app_id;
      $this->auth = $auth;
      $this->timeout = 60;
      $this->options = array();

      /** if ssl is off, replace https with http **/
      if (!(self::$standard_opts['ssl']))
        $this->endpoint = preg_replace("/https/","http",$this->endpoint);
    }

    /**
     * Set an option
     * for request
     *
     * @param $k -> string
     * @param $t -> mixed
     */
    protected function setOption($k, $t)
    {
      $this->options{$k} = $t;
    }

    /**
     * Return all options assigned
     * to element k. These are all CURL
     * properites and can be found @
     *
     *
     * @param k curl option
     */
    protected function options($k)
    {
      return $this->options{$k};
    }


    /**
     * Set the Catapult endpoint to be used
     *
     * To reset to the default use:
     * Catapult\RESTClient::endpoint();
     *
     * @param endpoint: endpoint to use
     */
    public static function endpoint($endpoint=API::API_ENDPOINT)
    {
      self::$standard_opts['endpoint'] = $endpoint;
    }


    /**
     * Whether to use
     * ssl or not. To extend
     * support of SSL you can use
     * a private key file which will
     * be used in conjuction with the RESTclient.
     * Catapult\RESTClient::ssl(TRUE)
     * Catapult\RESTClient::ssl_key("./path_to_key");
     *
     * To turn off (use no ssl):
     * Catapult\RESTClient::ssl(FALSE);
     *      
     * @param on: TRUE|FALSE
     */
    public static function ssl($on=TRUE)
    {
      self::$standard_opts['ssl'] = $on;              
    }

    /**
     * Set the SSL key file
     * used in CURL requests.
     * 
     * @param file: partial or fully qualified file path
     */
    public static function sslKey($file)
    {
      self::$standard_opts['ssl_key'] = realpath($file);
    }

    /**
     * Do we need to verify
     * this SSL request? By default
     * we only do if there is a key
     * provided. If not, no
     *
     * @param verify: TRUE|FALSE
     */
    public static function verify($verify=TRUE)
    {
      self::$standard_opts['verify'] = $verify;
    }

   /**
    * Concatenate URL according to Catapult endpoints
    *
    * In some cases we dont need to join,
    * As a result this should only provide the user id, and base string
    * @param url: partial url
    */
    public function join($url, $users=TRUE)
    {
      if ($users)
        return $this->endpoint . "/v1/users/" . $this->uid . "/" . $url;

      return $this->endpoint . "/v1/". $url; 
    }

   /**
    * Handle the headers given
    * successful response
    *
    * @param headers -> string ":" seperated headers
    */
    private function headerHandler($headers)
    {
      return $headers;
    }

   /**
    * This will try a request, when a request is returned
    * parse and when an empty string is encountered return headers 
    * when we have content return it as either raw or a json object -- this
    * varies with the Content-Type received in response
    *
    * @method -> STRING [GET, POST, PUT (to implement)]
    * @data -> array [string k,v only]
    * @mixed -> whether to use GET parameters in place of POST or not
    * @returns -> headers | json content | raw content
    */
    private function request($method, $url, $data=array(), $mixed=FALSE, $decode=FALSE)
    {
      $this->hndl = curl_init();
      curl_setopt($this->hndl, CURLOPT_SSL_VERIFYPEER, self::$standard_opts['verify']);
      curl_setopt($this->hndl, CURLOPT_RETURNTRANSFER, 1);
      curl_setopt($this->hndl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
      curl_setopt($this->hndl, CURLOPT_HEADER, TRUE);

      /** if we're using an ssl key include it. **/   
      /** also verify the host in addition to the peer **/
      if (self::$standard_opts['ssl_key'] && self::$standard_opts['ssl']) {
        curl_setopt($this->hndl, CURLOPT_CAINFO, self::$standard_opts['ssl_key']);
        curl_setopt($this->hndl, CURLOPT_SSL_VERIFYPEER, 1);
        curl_setopt($this->hndl, CURLOPT_VERIFYHOST, 2);
      }

      if ($method == "POST") { 
        if (!is_string($data))
                $pure = json_encode($data);
        else
                $pure = $data;

        curl_setopt($this->hndl, CURLOPT_POSTFIELDS, (string) $pure);
        curl_setopt($this->hndl, CURLOPT_HTTPHEADER, array(
          'Content-Type: ' . $this->interop,
          'Content-Length: ' . strlen((string) $pure)
        ));

      } else if ($method == "PUT") {
        curl_setopt($this->hndl, CURLOPT_CUSTOMREQUEST, "PUT");
        curl_setopt($this->hndl, CURLOPT_POSTFIELDS, (string) $data);
        curl_setopt($this->hndl, CURLOPT_HTTPHEADER, array(
                'Content-Length: ' . strlen((string) $data)
        ));
      } else if ($method == "DELETE") {
        curl_setopt($this->hndl, CURLOPT_CUSTOMREQUEST, "DELETE");
      }

      /* branch seperatly as POST may need GET parameters */
      if ($method == "GET" || $mixed) {
        $params = "";
        if (sizeof($data)>0) {
         $params = "?" . http_build_query($data);
        }
        $url .= $params;
      }
      curl_setopt($this->hndl, CURLOPT_URL, $url);

      /* setup auth */
      curl_setopt($this->hndl, CURLOPT_USERPWD, $this->auth[0] . ':' . $this->auth[1]);
      $raw = curl_exec($this->hndl);
      $hlen = curl_getinfo($this->hndl, CURLINFO_HEADER_SIZE);
      $header =substr($raw, 0, $hlen);
      $noformat = substr($raw, $hlen, strlen($raw) - $hlen);
      $content = json_decode($noformat);
      $headers = array();

      /* take the headers out */
      /* if we're left with nothing. return headers and dont encode */
      $lines = explode("\n", $header);
      foreach ($lines as $l) {
        $h = explode(": ", $l, 2); 

        if (isset($h[1]))
          $headers[$h[0]] = trim(str_replace("\r\n", "", str_replace("\n", "", $h[1]))); 
      }

      /* add support for header only responses where information is only found in "location" */
      /* in some cases we need the raw content (Media Files) */
      if (isset($headers['Content-Type']) && in_array($headers['Content-Type'], self::$media_formats)) {
        return $noformat;
      }

      if (is_object($content) || is_array($content)) {
        $res = $content;
      } else {
        $res = $headers;
      }

      $code = (int) curl_getinfo($this->hndl, CURLINFO_HTTP_CODE);

      if (curl_errno($this->hndl) != 0)
        throw new \CatapultApiException("Request was unproperly configured");

      /* a 200s status for a succesful request */     
      if (!($code >= 200 && $code <= 299))
        throw new \CatapultApiException(json_encode($res));

      /* should be either blank (when a successful post) or a json object */
      if (!(is_object($res) || is_array($res) || $res == ""))
        throw new \CatapultApiException("Inteoperability format does not match.");

        return $res;
      }

    /**
     * Perform get requests
     * against Catapult API.
     * 
     * 
     * @param -> url string [partially qualified]
     * @param -> join bool
     */
    public function get($url, $params=array(), $join=TRUE, $users=TRUE)
    {
      if ($join && $users)
        $url = $this->join($url);
      if ($join && !$users)
        $url = $this->join($url, FALSE);

      return $this->request(API::API_METHOD_GET, $url, $params);
    }

    /**
     * POST request
     *
     * @param -> url [partially qualified]
     * @param -> join boolean
     */
    public function post($url, $data=array(), $join=TRUE, $users=TRUE, $mixed=FALSE)
    {
      if ($join && $users)
        $url = $this->join($url);
      if ($join && !$users)
        $url = $this->join($url, FALSE);

      $this->setOption('auth', TRUE);
      $this->setOption('headers', TRUE);

      if ($mixed)
        return $this->request(API::API_METHOD_POST, $url, $data, $mixed);

      return $this->request(API::API_METHOD_POST, $url, json_encode($data), $mixed);

    }

    /**
     * PUT request
     *
     * @param -> url [partially qualified]
     * @param -> join boolean
     */
    public function put($url, $data)
    {       
      $url = $this->join($url);
      return $this->request(API::API_METHOD_PUT, $url, $data);
    }

    /**
     * DELETE request
     *
     * @param -> url [partially qualified]
     * @param -> join boolean
     */
    public function delete($url, $data=null)
    {
      $url = $this->join($url);
      return $this->request(API::API_METHOD_DEL, $url, $data);
    }
          
}
