<?php
namespace Catapult;


/**
 * Provides the base for 
 * helper resources. 
 *  
 */
abstract class BaseResource { }

/**
 * GenericResource is the class every model
 * should use for initialization. It provides
 * the functions to check the mandatory, optional
 * arguments provided 
 *
 */ 
class GenericResource { 

    public $lastUpdate;

    public static $paths = array(
        "Application" => "applications",
        "Call" => "calls",
        "Conference" => "conferences",
        "ConferenceMember" => "members",
        "UserError" => "errors",
        "Message" => "messages",
        "PhoneNumbers" => "phoneNumbers",
        "Recording" => "recordings",
        "Account" => "account",
        "Transcription" => "transcriptions",
        "Bridge" => "bridges",
        "NumberInfo" => "numberInfo",
        "Transaction" => "transactions",
        "Domains" => "domains",
        "Endpoints" => "endpoints",
        "Media" => "media",
        "CallEvents" => "events",
        "Gather" => "gather"
    );


     /**
      * accomodate for camelcase vs underscore
      *
      * update adds prototyped functions for
      * quicker mockups
      *
      * @param function a underscored function
      * @return this->camelizedContext
      * ex: $call->listCalls -> $call->list_calls
      *
      */
    public function __call($function, $args)
    {
      /** run the subfunctions first **/
      $sub = $this->get_sub_function($function);
      if ($sub) {
        $sargs = array_merge($args, array($sub->plural, $sub->term, &$this));


        /**
         * prototype the function and
         * run it. We take the term and amount
         */
        return call_user_func_array("Catapult\\PrototypeUtility::" . $sub->type, $sargs);
      }


      $glue = "";
      $m = array();
      preg_match("/([a-z]+)[A-Z]/", $function, $m);
      
      if (sizeof($m) > 0)	
        $glue .= $m[1];	


      /** list function call model's list **/
      if ($glue == "list")
        return $this->_list($args);


      $m = array();
      preg_match_all("/[A-Z]{1,}[a-z]+/", $function, $m);		
      if (sizeof($m) > 0) {
        foreach ($m[0] as $m1) {
          $glue .= "_" . strtolower($m1);
        }
      }

      if (method_exists($this, $glue))
        return $this->{$glue}($args);
      else
        throw new \CatapultApiException("function: $function not found in " . get_class($this));
    }

    /**
     * _init performs basic
     * functions to set up all
     * the subclasses. Will call the 
     * normal execution order.
     *
     * - first attach the client
     * - check if the schema is properly formed
     * - initialize path with dependancies
     * - return the appropriate object use in resolver
     * added: 1/29/2015
     *
     */

    public function _init($data, $depends, $loads, $schema, $extras=null) {
      $plain = $data->get();      
      $input_is_str = $data->is_string();
      $double_string = $data->is_double_string();
      /*
       * replace all numerical keys with the ones defined in schema    
       *
       * this is to support backwards compatilbility and 
       * add trivial loading for certain objects.
       *
       * i.e 
       * Gather('call_id', array(params))
       * same as
       * Gather(array('call_id' => '', params))
       */

      /** don't overload a 'GET' which is '1' in arity. This can be 2+ **/
      if (sizeof($plain) > 1 && !$double_string) {
        foreach ($plain as $k => $p) {
          if (is_numeric($k) && isset($loads->init[$k])) {
            $plain[$loads->init[$k]] = $p;
            unset($plain[$k]);
          }
        }
      }
      /**
       * another backwards compatable thing 
       * arguments were passed as singular and
       * id being intialized is the parent's
       * so it should be:
       * Gather('call_id')
       * and not:
       * Gather('gather_id')
       *
       * other resources, i.e calls would not need there
       * conference id to be initialized thus can be called normally
       * 
       * 
       * this is only for models that use other models.
       * without their main resource's id they cannot
       * do anything.
       */
      if ($input_is_str && $loads->silent && !$double_string) {
        $plain = array($loads->init[0] => $plain);
      }


      /**
      * two based arguments
      * Gather('call_id', 'gather_id')
       * 
      * this is for resources that need there parent
      * resource to operate
      */
      if ($double_string) {
        $plain = array($loads->init[0] => $plain[0], "id" => $plain[1]);
      }


      $pargs = func_get_args();

      /** string endpoint has been added in params **/
      $cl = preg_replace("/^Catapult\\\/", "", get_class($this));

      $bpath = self::$paths[$cl]; 


      $sets = array(
       "depends" => $depends, "loads" => $loads, "schema" => $schema, "path", $bpath
      );


      foreach ($sets as $k => $s) 
          $this->$k = $s;
      $this->path = $bpath; 

      $this->subfunctions = $extras;    

      /** attach the main client to this object **/
      ClientResource::attach($this);

      /** use depends **/
      PathResource::make($this, $plain);

      /** use schema **/
      VerifyResource::verify($this, $plain);

      /** attach functions **/
      //FunctionResource::register($extras);

      Resolver::find($this, $plain);

      /** dispose these resources they can take some space **/
      /** we only need the client now **/
      foreach ($sets as $k => $s) 
        unset($this->$k);

    }


    /**
     * checks if a given term lands
     * in subfunctions 
     *
     * @param fn: full function name
     */
    public function get_sub_function($fn)
    {
      if (!isset($this->subfunctions))
        return false;

      foreach ($this->subfunctions->terms as $sfn) {
        $pred = $sfn->type . TitleUtility::toTitleCase($sfn->term);

        if ($pred == $fn)
          return $sfn;
       }

      return false;
    }


    /**
     * Public API get, this will
     * call the get function on 
     * the loaded endpoint, optionally
     * add another id if specified
     * 
     *
     * @param id: id to load
     */
    public function get($id=NULL) 
    {
      if (!$id) {
        if (!isset($this->id)) {
          $url = URIResource::Make($this->path);
        } else {
          $url = URIResource::Make($this->path, array($this->id));
        }

        $data = new DataPacket($this->client->get($url));
      } else { 
        $url = URIResource::Make($this->path, array($id));
        $data = new DataPacket($this->client->get($url));
      }

      return Constructor::Make($this, $data->get());
    }


   /**
    * default string representation
    * of object.
    *
    * TODO: move to StringifyResource
    *
    * @return string that represents the object
    */
    public function __toString()
    {
      /** keys to not include **/
      $not = array("primary_method", "lastUpdate", "client");
      $keys = get_object_vars($this);
      $str = str_replace("Catapult\\", "", get_class($this)) . "{";
      foreach ($keys as $k => $v) {
        if (is_string($this->$k) && !in_array($k, $not))
          $str .= $k . "='" . $this->$k . "',";
      }

      return substr($str, 0, strlen($str) - 1) . "}";
    }

    /**
     * Generic list. Moved from independant
     * declaration, should work on all models
     *
     * @param args arguments with page, and size [optional]
     */
    public function _list($args=null) 
    {
      $data = Ensure::Input($args);
      $data = $data->get();
      if (isset($data[0])) {
        $data = $data[0];
      }
      if (!(isset($data["size"]))) {
        $data["size"] = DEFAULTS::SIZE;			
      }
      if (!(isset($data["page"]))) {
        $data["page"] = DEFAULTS::PAGE;
      }
      $url = URIResource::Make($this->path);
      $class = get_class($this) . "Collection";

      return new $class(new DataPacketCollection($this->client->get($url, $data)));
    }


    /**
     * General purpose create
     *
     * @param args for model's create
     * see object for more
     */
    public function create()
    {
      $data = Ensure::Input(func_get_args());
      $url = URIResource::Make($this->path);
      $id = Locator::Find($this->client->post($url, $data->get()));
      $data->add("id", $id);

      return Constructor::Make($this, $data->get(), TRUE);
    }

    public function update()
    {
      $data = Ensure::Input(func_get_args());
      $url = URIResource::Make($this->path, array($this->id));
      $this->client->post($url, $data->get());

      return Constructor::Make($this, $data->get(), TRUE);
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
     * delete a media
     * file
     *
     * TODO: this should not keep the object's 
     * information. This will not be useful anymore!
     *
     * @param id of model 
     */
    public function delete($id=null)
    {
      if ($id) {
        $url = URIResource::Make($this->path, array($id));
      } else {
        $url = URIResource::Make($this->path, array($this->id));
      }

      $this->client->delete($url);
    }

    /**
     * load the object with static properties
     * usually done client side -- or by collections
     * already holding information on an object
     * 
     * @param props -> set of properties to load
     */
    public function load()
    {
      $args = Ensure::Input(func_get_args());
      $props = $args->get();
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
     * gets the audio
     * url for a resource
     * Usually this is extended by a model
     *
     */
    public function getAudioUrl()
    {
        return URIResource::Make($this->path, array($this->id));
    }

   /**
    * 
    * helper
    * get the path for a given class
    * fallback: plural lowercased
    *
    */
    public function getPath($class)
    {
      if (in_array($class, array_keys(self::$paths)))
        return self::$paths[$class];

      return strtolower(TitleUtility::toPlural($class));
    }

    /**
     * get the class for a given path 
     * fallback: singular titlecased
     */
    public function getObjClass($path)
    {
      $cnt = 0;
      foreach (self::$paths as $k => $p)
        if ($p == $path)
          return $k;


      return TitleUtility::toTitlecase(TitleUtility::toSingular($path));
    }

    /**
     * get the schema fields in a comma seperated
     * form 
     */
    public function getSchemaString($ctx=null) 
    {
      $str = "";
      if (!$ctx)
          $ctx = $this->schema->fields;

      foreach ($ctx as $f)
          $str.=$f. ",";

      return $str;
    }

   /**
    * make all 
    * properties an array
    *
    */
   public function toArray()
   {
      $proto = Converter::toArray($this);
      $not = array('primary_method', 'lastUpdate', 'subfunctions', 'client', 'path', 'hasPath');
      // we only need key value
      foreach ($proto as $k => $p) {
        if (in_array($k, $not)) {
          unset($proto[$k]);
        }
      }

      return $proto;
   }
}
