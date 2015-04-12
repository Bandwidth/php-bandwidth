<?php
namespace Catapult;
/**
 * @class BaMLResource
 * 
 * defines the base for BaML
 * generation  and parsing. 
 * Uses the PHP internal SAX parser
 * for XML. 
 *
 */

namespace Catapult;

abstract class BaMLResource {
    public static $options = array(
      BAML_XML_OPTIONS::BAML_XML_ENCODING => "UTF-8"
    );
    /** primary parser **/
    public $parser;
    public $fileHandler;
 
    /** primary data **/ 
    public $data;
   
    /** parsing tree variables **/ 
    /** what level what the element found on **/
    private $level = 0;
    /**
     * Generate the parser object
     * and tie to xml_set_object
     *
     * @param data: xml string when set use parsing otherwise treat as creation
     */
    public function __construct($data = null) {
      $this->parser = xml_parser_create();        
      xml_set_object($this->parser, $this);
    }
    /**
     * valid will check if this is valid
     * according to BaML
     *
     * @param valid: BaML xml struct item with level, name and attributes
     */
    public function register($element, $container=false) {
      if (in_array($element['tag'], array("Request", "Response")))
        return new BaMLContainer($element['tag']);
      if (!(in_array($element['tag'], BaMLVerb::$valid)))
        throw new \CatapultApiException($element['tag'] . " is not a valid verb..");
      if ($container) {
        $class = "Catapult\\" . "BaMLVerb" . $element['tag']; 
      } else {
        $class =  "Catapult\\" . "BaMLVerb" . $element['tag']; 
      }
      $class = new $class;
      if (isset($element['attributes'])) {
         foreach ($element['attributes'] as $k => $attr) {
           $class->addAttribute($k, $attr);
         }
      }
      if (isset($element['value']))
        $class->setText(trim($element['value']));
      $class->level = $element['level'];
      return $class;
    }
    /**
     * parse the provided text.
     * generate a parser here
     * we usually wouldnt need to 
     * have one initialized with the
     * object.
     *
     * @param text: valid BaML markup
     */
    public function parse($text) {
      $this->queue = array();
      $this->parser = xml_parser_create();        
      $this->parsed = 0;     
      $this->opened = 0;
      xml_set_object($this->parser, $this);
      xml_parser_set_option($this->parser, XML_OPTION_CASE_FOLDING, 0);
      xml_parser_set_option($this->parser, XML_OPTION_SKIP_WHITE, 1);
      $code = xml_parse_into_struct($this->parser, $text, $vals, $index); 
      /**
       * Parse into bAML
       * type structure
       * 
       */ 
      if ($code != 0) {
        return XMLUtility::Make($this, $vals); 
      }
        
      throw new \CatapultApiException("Provided text was not valid XMLUtility..");
    }
    /**
     * reads a file, transferings
     * into a BaML object
     *
     * @param file: absolute or relative filepath
     */
    public function getAsStream($file) {
      $this->parse(FileHandler::read($file));
    }
    /**
     * add data
     * to an existing
     * BaML object
     *
     * @param data: BaMLAttribute or BaMLText or BaMLVerb
     */
    public function addData($object) {
      $this->data[] = $object;
    }
    /**
     * stub. goto __toString
     *
     */
    public function generate() {
      return $this->__toString();
    }
    /**
     * get the contents
     * for this baml
     * object
     */
    public function get() {
      return $this->data;
    }
    /**
     * Things to consider when
     * generating toString
     * parameters will always be
     * located as a member of this instance
     * 
     * parameters should be in 
     * xml mode and according to
     * schema either 
     * wrap in Response|Request
     */
    public function __toString() {
      $str = "";
      $start = 1;
      $str .= XMLUtility::getHeader();
      $container = $this->data[0];
      /** root element is not a container, add **/
      if (!($container instanceof BaMLContainer)) {
          $container = new BaMLContainer;
          $start = 0;
      }            
      $container->verbs = array_merge($container->verbs, array_slice($this->data, $start, sizeof($this->data)));
      $str .= XMLUtility::openTag($container->getType());
      /**
       * if the structure is 
       * flat we need to take the remaining
       * pieces and merge with the container
       */
      $this->data = $container->verbs;
      foreach ($this->data as $bamlObj) {
        if ($bamlObj instanceof BaMLVerb) {
         $str .= XMLUtility::openTag($bamlObj->getName(), $bamlObj->getAttributes());                
         $str .= XMLUtility::joinTree($bamlObj->getVerbs());
         $str .= (string) $bamlObj->getText();
         $str .= XMLUtility::closeTag($bamlObj->getName()); 
        }
      }
      $str .= XMLUtility::closeTag($container->getType());
      return ($str);
    }
}
/**
 * @class BaML
 *
 * main class to access BaML
 * features should be extended
 * by each verb and provide logic
 *
 */
final class BaML extends BaMLResource {
    public $parameters;
    /** keep track of BaML sequentialy **/
    public function __construct($type='Response') {
      parent::__construct();
      $this->generateData($type);
      $this->generateParameters();
    }
    public function generateParameters() {
       
      $this->parameters = new Parameters; 
    }
    /**
     * initial add
     * of container to data
     */
    public function generateData($type) {
      $this->addData(new BaMLContainer($type));
    }
    /**
     * do we have a verb?
     *
     */
    public function hasCurrentVerb() {
      if ($this->currentVerb == NULL)
        return False;
      return true;
    }
    /**
     *
     * public API method. Get the data
     * in the BaML container
     */
    public function get() {
      return $this->data;
    }
    /**
     * get all the verbs in this
     * check if its a flat array or 
     * has multiple layers
     */
    public function getVerbs() {
      if (sizeof($this->data) > 1) {
        return array_merge($this->data[0]->verbs, array_slice($this->data, 1, sizeof($this->data)));
      }
      return $this->data[0]->verbs;
    }
    /**
     * parse a line
     * and add to document
     * @param line 
     */
    public function addLine($line) {
      $this->trySet(XMLUtility::parse($line));
    }
    /**
     * get the current
     * verb.
     *
     */
    public function getCurrentVerb() {
      return $this->currentVerb; 
    }
    /**
     * get the current
     * text
     *
     */
    public function getCurrentText() {
      $this->currentText = $object;
    }
    /**
     * set current verb
     *
     * @param object
     */
    public function setCurrentVerb(&$object) {
      $this->currentVerb = $object; 
    }
    /**
     * set the current text
     * for baml
     */
    public function setCurrentText(&$object) {
      $this->currentText = $object;
    }
    /**
     * handle attributes
     * 
     */
    private function handleAttributes($object) {
      if (isset($object['attributes']) && sizeof($object['attributes']) > 0) {
        foreach ($object['attributes'] as $val => $attr) {
          $this->addData(new BaMLAttribute($val, $attr)); 
        }
      } else {
          /** warn on no attributes no throw **/ 
        return new \CatapultApiWarning("Attributes were not provided for " . get_class($object));
      }
    }
    /**
     * handle an object
     * provided from baml's add funtion
     *
     * @param object
     * @param 
     */
    private function handleObject($object, $type=null) {
        if (is_object($object)) {
          if (!($object instanceof BaMLAttribute || $object instanceof BaMLText || $object instanceof BaMLVerb)) {
            throw new \CatapultApiException("Not a valid object in BaML schema. Must be either BaMLAttribute, BaMLVerb or BaMLVerb");
          }
          if (!isset($this->currentVerb)) {
            $this->setCurrentVerb($object);
          }
          if (!isset($this->currentText)) {
            $this->setCurrentText($object);
          }
           
          $this->addData($object);
        } else {
            /** for this initialization we need type to be set **/
          $this->addData($object); 
        }
    }
    /**
     * sets a verb. Generate
     * a new class from BaMLVerb
     * if we have a current verb
     * we cannot add a new one
     */
    public function setVerb($params) {
      return $this->handleObject($params);
    }
    public function setText($params) {
      if (!($this->hasCurrentVerb())) {
        throw new \CatapultApiException("Verbs must always be set before other texts..");
      }
      return $this->handleObject($params);
    }
    /**
     *
     * for setting an attribute
     * you can also use the text
     * as the second parameter
     */
    public function setAttribute($params) {
      if (!($this->hasCurrentVerb())) {
        throw new \CatapultApiException("Verbs must always be set before other attributes..");
      }
      if (!($this->hasCurrentText())) {
        throw new \CatapultApiException("Texts must always be set before other attributes..");
      }
      return $this->handleObject($params);
    }
    /**
     * set the container's
     * type.
     * it has to be in the
     * first element of
     * data
     * @param type: string (Request|Response)
     *
     */
    public function setContainerType($type) {
      $this->data[0]->setType($type);
    }
    /**
     * try setting an object
     * in the namespace
     * if we cant throw
     */
    public function trySet($object) {
      /** if we're passed one argument pluck its object **/
      if (sizeof($object) == 1)
        $object = $object[0];
       if (is_array($object)) {
         /** either its a verb, text or attribute **/
         /** we need to find type by its name **/
              
         /** create the object and any additional attributes **/
         /** attributes are set in "attributes" field of array **/
         $this->handleAttributes($object); 
       } elseif (is_object($object)) {
         
        /** object based initialization **/       
        $this->setVerb($object); 
       } elseif (is_string($object)) {
          /** polymorphic style initialization **/
          /** first parameter needs to be the name of the instance **/
          /** following are attributes **/
        $args = array_slice($object[1], $object[sizeof($object) - 1]);
            
       } else {
          throw new \CatapultApiException("Invalid input for baML must be either string, instance or associative array");
       }
    }
    
    /** call the handler as a parameter, always consider the verb **/
    /** deal with setVerb, and setText differently **/
    /** attributes, and texts should always have the verb set first **/
    /** 
     * do this by verifying class currentVerb is always 
     * loaded first
     */
     
    public function __call($function, $args) {
      /**
       * if we only have a set
       * parameter try to take
       * by instance. like
       * $bamlobject->set(new Catapult\BaMLVerbSpeakSentence);
       */
      if ($function == "add" || $function == "set") {
          return $this->trySet($args);
      }
      /** branch to attribute setting here **/
      $this->parameters->__call($function, $args);
    }
}
