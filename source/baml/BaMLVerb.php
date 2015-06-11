<?php
/**
 * @class BaMLVerb
 * 
 * Define the object behind verbs.
 * inpendant verb objects should be able to access
 * their directly nested elements and should 
 * should all have the following functions: 
 *
 * addVerb/1
 * addNestedVerb/2
 * addAttribute/1
 * addNestedAttribute/2
 * addText/1
 * addNestedText/2
 * 
 * 
 */
namespace Catapult;

class BaMLVerb extends BaMLGeneric {
    /** use constants here for clarity, and ability to interchange **/
    public static $valid = array(
        BAML_VERBS::BAML_SPEAK_SENTENCE, 
        BAML_VERBS::BAML_PLAY_AUDIO, 
        BAML_VERBS::BAML_TRANSFER, 
        BAML_VERBS::BAML_GATHER, 
        BAML_VERBS::BAML_RECORD,
        BAML_VERBS::BAML_SEND_MESSAGE, 
        BAML_VERBS::BAML_REDIRECT, 
        BAML_VERBS::BAML_HANGUP, 
    );
    /** constraints should be by objects **/
    public $constraints = array(
    );
    public static $params = array( 
    );
    /** for the parser only **/
    private $parsed = null;
    /** name as a string **/
    private $name = "";
    /** text of BaML object **/
    public $text = null;
    /** flat array of verbs for this verb **/
    public $verbs = array(
    );
    /** attributes for the BaML object **/
    private $attributes = array(
    );
    
    /**
     * Get the verb's class
     * name and provide it's
     * schema. 
     * 
     *
     * when we have an invalid
     * verb we should warn
     */
    public function __construct() {
      $params = func_get_args();
      /** this was not initiated implicitly **/
      /** we need to extract the class name **/
      $verb = preg_replace("/Verb/", "", preg_replace("/Catapult\\\BaML/", "", get_class($this)));
      $this->name = $verb;
      if (!(in_array($verb, self::$valid) || in_array("Verb" . $verb, self::$valid)))
        Throw new \CatapultApiException($verb . " is not a valid verb in baML v" . BAML_SETTINGS::BAML_VERSION);
      if ($params !== null) {
        /**
         * accept parameters for verbs and attributes
         * on init
         * first text is verbs second is 
         * attributes
         */
        $cl = get_class($this);
        foreach ($params as $cnt => $param) {
          $this->addAttribute($cl::$params[$cnt], $param);
        }
                      
     }
    }
    /**
     * BaML object from a given string
     * where string is a legal verb name
     *
     *
     */
    public function fromString($verb) {
      $verb_class = "Catapult\\BaMLVerb" . $verb;
      if (!(in_array($verb, self::$valid)))
        Throw new \CatapultApiException($verb . " is not a valid verb in baML v" . BAML_SETTINGS::BAML_VERSION);
      return new $verb_class;
    }
    /**
     * BaML Object in string form
     */
    public function __toString() {
      $name = $this->name . "{";
      foreach ($this->attributes as $attr) {
        $name .= $attr->getKey() . "='" . $attr->getValue() . "',";
      }
      return substr($name, 0, strlen($name) - 1) . "}";
    }
    /**
     * similar to public api
     * functions. create takes
     * accepted input as per (ensureResource)  
     * and will form the object from it
     * @param data
     */
    public function create($arg) {
      $data = Ensure::Input($arg);
      $args = $data->get();
      foreach ($args as $k => $arg) {
        if (is_string($arg)) {
          $this->addAttribute($k, $arg);
        }
        if ($arg instanceof BaMLVerb) {
          $this->addVerb($arg);
        }
        if ($arg instanceof BaMLAttribute) {
          $this->addAttribute($arg);
        }
        if ($arg instanceof BaMLText) {
          $this->addText($arg);
        }
      }
    }
    /**
     * are there nested verbs?
     */
    public function hasVerbs() {
      if (count($this->verbs) > 0)
        return TRUE;
      return FALSE;
    }
    /**
     * get the nested 
     * verbs
     *
     */
    public function getVerbs() {
      return $this->verbs;
    }
    /**
     * get the verb's name
     *
     */
    public function getName() {
      return $this->name;
    }
    /**
     * get all a verb's
     * attributes
     */
    public function getAttributes() {
      return $this->attributes;
    }
    /**
     * get the attributes
     * in one string
     */
    public function getAttributesString() {
      $str = "";
      foreach ($this->attributes as $attr) {
        $str .= $attr->getKey() . "='" . $attr->getValue() . "',";
      } 
      return substr($str, 0, strlen($str) - 1);
    }
    /**
     * get the text the verb
     * 
     *
     */
    public function getText() {
      return $this->text;
    }
    /**
     * add one verb to another
     * unless unapplicable
     *
     *
     *
     * @param verb: BaMLVerb
     */
    public function addVerb($verb) {
      if (is_array($verb)) {
        $verb = BaMLVerbResource::Make($verb);
      }
          
      if (!($verb instanceof BaMLVerb)) {
        throw new \CatapultApiException("You can only add type BaMLVerb..");
      }
      /** check for constraints **/
      if (isset($this->constraints['verbs']) && sizeof($this->verbs) >= $this->constraints['verbs']) {
        throw new \CatapultApiException($this->getName() . " verb can only take " . $this->constraints['verbs'] . " verbs");
      }
      /** avoid memory issues by cloning. if needed **/
      if (BaseUtilities::is_ref($verb, $this)) {
        $this->verbs[] = clone $verb;
      } else {
        $this->verbs[] = $verb;
      }
    }
    /**
     * adds a nested
     * verb to one
     * of this verb's
     * children
     *
     * @param index: between lower and upper
     * @param verb: BaML verb
     */
    public function addNestedVerb($index, $verb) {
      if (!($index <= sizeof($this->verbs))) {
        throw new \CatapultApiException("This verb does not have $index nested verbs");
      }
      
      $this->verbs[$index]->addVerb($verb);
    }
    /**
     * adds text to
     * a nested verb
     */
    public function addNestedText($index, $text) {
      if (!($index <= sizeof($this->verbs))) {
        throw new \CatapultApiException("This verb does not have $index nested verbs");
      }
      $this->verbs[$index]->addText($text);
    }
    public function setNestedText($index, $text) {
      if (!($index <= sizeof($this->verbs))) {
        throw new \CatapultApiException("This verb does not have $index nested verbs");
      }
      $this->verbs[$index]->setText($text);
    }
    /**
     * add a nested
     * attribute
     */
    public function addNestedAttribute($index, $attribute) {
      if (!($index <= sizeof($this->verbs))) {
        throw new \CatapultApiException("This verb does not have $index nested verbs");
      }
      $this->verbs[$index]->addAttribute($attribute);
    }
    public function addNestedAttributes($index, $attribute) {
      if (!($index <= sizeof($this->verbs))) {
        throw new \CatapultApiException("This verb does not have $index nested verbs");
      }
      $this->verbs[$index]->addAttributes($attribute);
    }
    /**
     * adds an attribute
     * to the verb.
     *
     * @param attribute: array | tuple parameter | BaMLAttribute
     *
     */
    public function addAttribute($attribute) {
      $args = func_get_args();
      $cl = get_class($this);
      if (is_array($attribute))
        $attribute = new BaMLAttribute($attribute[0], $attribute[1]);
      if (is_string($attribute))
        $attribute = new BaMLAttribute($args[0], $args[1]);
      if (!($attribute instanceof BaMLAttribute))
        throw new \CatapultApiException("You can only add type BaMLAttribute or array..");
      if (isset($cl::$params) && !(in_array($attribute->getKey(), $cl::$params)) && sizeof($cl::$params) > 0)
        throw new \CatapultApiException("attribute '" . $attribute->getKey() . "' is not a valid attribute for verb " . $this->getName() . "" . " please use any of the following: " . $this->printAttributes());
      $this->attributes[] = $attribute;
    }
    public function printAttributes() {
      $cl = get_class($this);
      $attrs = $cl::$params;
      $str = "";
      foreach ($attrs as $attr) {
          $str.="$attr,";
      }
      return substr($str, 0, strlen($str) - 1);
    }
    /** add attributes in multi form **/
    public function addAttributes($attributes) {
      foreach ($attributes as $attr) {
          $this->addAttribute($attr);
      }
    } 
    /**
     * add text. This will append
     * @param text
     */
    public function addText($text) {
      if (is_string($text))
          $text = new BaMLText($text);
      if (!($text instanceof BaMLText)) 
          throw new \CatapultApiException("You can only add type BaMLAttribute or array..");
      
      $this->text = $this->text . $text;
    }
    /**
     * counts all the
     * verbs
     *
     */
    public function countVerbs() {
      return sizeof($this->verbs);
    }
    /**
     * gets a nested
     * verb
     *
     */
    public function getNestedVerb($index) {
      return $this->verbs[$index];
    }
   
    /**
     * What level was this verb
     * found on. It is relative
     * to the BaML parsed document
     *
     */ 
    public function getLevel() {
      return $this->level;
    }
    /**
     * in some cases, for the
     * xml generation we need a  
     * nested verbs nested
     */
    public function getNestedNestedVerb($index) {
      return $this->verbs[$index];
    }
    public function setText($text) {
      if (is_string($text))
          $text = new BaMLText($text);
      if (!($text instanceof BaMLText)) 
          throw new \CatapultApiException("You can only add type BaMLAttribute or array..");
      $this->text = $text;
    }
}
