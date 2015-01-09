<?php
namespace Catapult;

/**
 * Support for Catapult's BAMl
 *
 * example usage:
 * $bobject = new Catapult\BaML\Message;
 * $bobject->setVerb("Response");
 * $bobject->setText("SpeakSentence", "Paul");
 * $bobject->setParam("voice", "Paul");
 * or 
 * $bobject->setAttribute("voice", "Paul");
 *
 * BaML objects are passed in the interface
 * just as regular arrays, Parameter objects
 *
 * receiving BaML objects
 * $object = new Catapult\BaML("<xml><response><SpeakSentence></SpeakSentence></response>");
 *
 *
 */


/**
 * Extend PHP's native SAX 
 * parser and provide an OOP
 * like interface to it
 */
abstract class BaMLResource {

    /** methods we need to use when constructing **/
    public static $methods = array(
        BAML_XML_METHODS::XML_CHARACTER_DATA_HANDLER
    );

    /** handlers for methods **/
    public static $handlers = array(
        BAML_XML_HANDLERS::BAML_PARSE_CHARACTER,
    );


    /** primary parser **/
    public static $parser;

    public $fileHandler;

 
    /** primary data **/ 
    public $data;
   
    /** parsing tree variables **/ 
    private $last_ch = null;
    private $last_ch2 = null;
    private $last_tag = null;
    private $last_text = "";
    private $current = null;
    private $children = 0;

    public $queue = array();

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
     * @param valid: BaML element
     */
    public function register($element) {

    }

    /**
     * arrange a struct
     * of xml elements into
     * trees
     *
     * @param data: flat array
     */
    public function intoTree($data) {

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
          //xml_set_default_handler($this->parser, 'parseCdata');
          //xml_set_element_handler($this->parser, 'parseElementStart', 'parseElementClose');
          xml_parser_set_option($this->parser, XML_OPTION_CASE_FOLDING, 0);
          xml_parser_set_option($this->parser, XML_OPTION_SKIP_WHITE, 1);
          xml_parse_into_struct($this->parser, $text, $vals, $index); 

          
          /**
           * Parse into bAML
           * type structure
           * 
           */ 
          
          if ($code == 0) {
                return new BaML; 
          }


          throw new \CatapultApiException("Provided text was not valid XML..");
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
     *
     * opens a tag for
     * the XML document
     *
     * @param attributes: when provided will fill attributes as well
     */
    public function openTag($name, $attributes=null) {

         if ($attributes) {
                $initial = $name;

                $name = XML::open($initial);

                $name .= XML::getAttributesCollection($attributes);

                $name .= ">";

                return $name;
         } 

         return XML::fullOpen($name);
    }

    public function closeTag($name) {
         return XML::fullClose("$name");
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
     * simple xml join tree
     * sax wrapper around PHP's XML parser does
     * not provide
     */
    public function joinTree($verbs) {
        $str = '';

        if (sizeof($verbs) > 0) {

                foreach ($verbs as $v) {
                  
                    $str .= self::openTag($v->getName(), $v->getAttributes());

                    if ($v->hasVerbs()) {
                        $str .= $this->joinTree($v->getVerbs());    
                    }

                    $str .= $v->getText();


                    $str .= self::closeTag($v->getName());
                }
        }

        return $str;
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

        $str .= XML::getHeader();
        $str .= self::openTag($this->data[0]->getType());

        foreach ($this->data as $bamlObj) {
              switch ($bamlObj) {
                   case $bamlObj instanceof BaMLVerb:

                        $str .= self::openTag($bamlObj->getName(), $bamlObj->getAttributes());                

                        $str .= self::joinTree($bamlObj->getVerbs());

                        $str .= (string) $bamlObj->getText();

                        /**
                         * get all the nested
                         * trees.
                         */
                        
                        $str .= self::closeTag($bamlObj->getName()); 

                   break;

                   default:
                   break;
              }
              
        }

        $str .= self::closeTag($this->data[0]->getType());

        //return $str;
        return XML::indent($str);

    }


}


abstract class BaMLGeneric { 
    public function setText($text) {

    }
    public function addText($text) {

    }
    public function getText() {
        return (string) $this->text;
    }
}

/**
 * container for response and
 * request. wrap with xml namespace
 * and encoding. BaML should always have one
 * only one.
 *
 * Make sure these are title cased
 */
class BaMLContainer extends BaMLGeneric {
        public static $valid = array(
            "Request",
            "Response"
        );
        public $type = "";
        public function __construct($type) {
            if (!(strtolower($type) == "request" || strtolower($type) == "response"))
                throw new \CatapultApiException("BaML container must be either Request or Response");

            $this->type = $type;
        }

        public function getType() {
            return $this->type;
        }

        public function setType($type) {
             if (!(strtolower($type) == "request" || strtolower($type) == "response"))
                throw new \CatapultApiException("BaML container must be either Request or Response");

             $this->type = $type;
        }
}
/**
 * general class for checking
 * valid rules in verbs, attributes
 * and texts
 */
abstract class BaMLAssert { }



/** 
 * Provide a standard interface
 * for all verbs 
 * to inherit. All verbs 
 * should know their texts and attributes
 */
abstract class BaMLVerb extends BaMLGeneric {

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
    public static $constraints = array(

    );

    public static $valid_attributes = array(

    );

    /** for the parser only **/
    private $parsed = null;
    public $children = 0;
    public $am = 0;

    /** name as a string **/
    private $name = "";

    public $text = null;

    public $verbs = array(

    );

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
    public function __construct($params=null) {

        /** this was not initiated implicitly **/
        /** we need to extract the class name **/
        $verb = preg_replace("/Catapult\\\BaMLVerb/", "", get_class($this));
        $this->name = preg_replace("/Verb/", "", $verb);


        if (!(in_array($verb, self::$valid)))
            Throw new \CatapultApiException($verb . " is not a valid verb in baML v" . BAML_SETTINGS::BAML_VERSION);

        if ($params !== null) {

            /**
             * accept parameters for verbs and attributes
             * on init

             * first text is verbs second is 
             * attributes
             */

            if (!(is_array($params))) {
                 throw new \CatapultApiException("You supplied wrong parameters to BaMLVerb. They be an array");
            }


            $this->text = $params[0]; 
            $this->verbs = $params[1];
            $this->attributes = $params[2];
                
            
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
            if (is_array($verb))
                $verb = BaMLVerbResource::Make($verb);
                
            if (!($verb instanceof BaMLVerb))
                throw new \CatapultApiException("You can only add type BaMLVerb..");

            /** check for constraints **/

            if (isset($this->constraints['verbs']) && sizeof($this->verbs) >= $this->constraints['verbs']) {
                throw new \CatapultApiException($this->getName() . " verb can only take " . $this->constraints['verbs'] . " verbs");
            }


            $this->verbs[] = $verb;
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

            if (is_array($attribute))
                $attribute = new BaMLAttribute($attribute[0], $attribute[1]);

            if (is_string($attribute))
                $attribute = new BaMLAttribute($args[0], $args[1]);

            if (!($attribute instanceof BaMLAttribute))
                throw new \CatapultApiException("You can only add type BaMLAttribute or array..");

            /*
            if (!(in_array($attribute, self::$valid_attributes) && sizeof(self::$valid_attributes) > 0))
                throw new \CatapultApiException("attribute '" . $attribute->getKey() . "' is not a valid attribute for verb " . $this->getName() . "");
            */

            $this->attributes[] = $attribute;
    }

    /** add attributes in multi form **/
    public function addAttributes($attributes) {
        foreach ($attributes as $attr) {
            $this->addAttribute($attr);
        }
    } 


    /** adds an text can only have one **/
    public function addText($text) {
            if (is_string($text))
                $text = new BamlText($text);

            if (!($text instanceof BaMLText)) 
                throw new \CatapultApiException("You can only add type BaMLAttribute or array..");
            
            $this->text = $this->text . $text;
    }

    public function setText($text) {
            if (is_string($text))
                $text = new BamlText($text);

            if (!($text instanceof BaMLText)) 
                throw new \CatapultApiException("You can only add type BaMLAttribute or array..");

            $this->text = $text;
    }
}

/**
 * object accept any 
 * number of parameters as 
 *
 *
 *
 * BaML texts need to check for CData
 * and xml encoding
 */
final class BaMLText extends BaMLAssert {
    public function __construct($text) {
        $this->text = $text;
    }

    public function __toString() {
        return $this->text;
    }
}

final class BaMLAttribute extends BaMLAssert {

    private $key;
    private $value;
    /** baML attributes do not set valid **/
    /** take a tuple as a key, value pair **/

    public function __construct($text, $val) {
           $this->key = $text;
           $this->value = $val;
    }

    public function getValue() {
           return $this->value;
    }
    public function getKey() {
           return $this->key;
    }
}


/**
 * BaML should be
 * an abstraction of Parameters
 * as well as BaMLResource. 
 *
 * It should provide the user end
 * user of BaML 
 */
final class BaML extends BaMLResource {
    public $parameters;

    /** keep track of BaML sequentialy **/
    public $currentText;
    public $currentVerb;

    public function __construct($type='Request') {
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


    public function addLine($line) {

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



               if (!isset($this->currentVerb))
                    $this->setCurrentVerb($object);

               if (!isset($this->currentText))
                    $this->setCurrentText($object);

               
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

final class BaMLVerbSpeakSentence extends BaMLVerb {}
final class BaMLVerbTransfer extends BaMLVerb {}
final class BaMLVerbPlayAudio extends BaMLVerb {}
final class BaMLVerbRecord extends BaMLVerb {}
final class BaMLVerbGather extends BaMLVerb {}
final class BaMLVerbSendMessage extends BaMLVerb {}
final class BaMLVerbRedirect extends BaMLVerb {}
final class BaMLVerbHangup extends BaMLVerb {}

?>
