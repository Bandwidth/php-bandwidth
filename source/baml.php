<?php
namespace Catapult;

/**
 * Support for Catapult's BAMl
 *
 * This currently supports the following BaML verbs:
 * SpeakSentence
 * PlayAudio
 * Transfer
 * Record 
 * Gather 
 * SendMessage 
 * Redirect
 * Hangup
 *
 * example usage:
 * $baml = new Catapult\BaML;
 * $baml->parse(XML_STRING)
 * 
 * OR 
 * $baml = new Catapult\BaML;
 * $verb = new Catapult\BaMLTransfer;
 * $baml->set($verb);
 */

/**
 * Extend PHP's native SAX 
 * parser and provide an OOP
 * like interface to it
 */
abstract class BaMLResource {
    public static $options = array(
         BAML_XML_OPTIONS::BAML_XML_ENCODING => "UTF-8"
    );


    /** primary parser **/
    public static $parser;

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

            if ($container) 
                $class = "Catapult\\" . "BaMLVerb" . $element['tag']; 
            else
                $class =  "Catapult\\" . "BaMLVerb" . $element['tag']; 

            $class = new $class;

            if (isset($element['attributes'])) {
               foreach ($element['attributes'] as $k => $attr) {
                  $class->addAttribute($k, $attr);
               }
            }
            if (isset($element['text']))
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
 * generic class for BaML
 * verbs, and containers
 */
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

        /** full set of root level verbs **/
        public $verbs = array(
        );

        /** always found on root level: 0 **/
        public $level = 0;

        /** either Request or Response **/
        public $type = "";
        public function __construct($type="Request") {
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
 * Provide a standard interface
 * for all verbs 
 * to inherit. All verbs 
 * should know their texts and attributes
 */
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
    public static $constraints = array(

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
                $text = new BamlText($text);

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
       if (sizeof($this->data) > 1)
            return array_merge($this->data[0]->verbs, array_slice($this->data, 1, sizeof($this->data)));

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

class BaMLVerbSpeakSentence extends BaMLVerb {
    public static $params = array(
        "sentence",
        "voice",
        "gender",
        "locale"
    );
}


class BaMLVerbTransfer extends BaMLVerb {
    public static $params = array(
       "transferTo",        
       "transferCallerId"
    );
}


class BaMLVerbPlayAudio extends BaMLVerb {
    public static $params = array(
       "audioUrl",
       "digits"
    );
}

class BaMLVerbRedirect extends BaMLVerb {
    public static $params = array(
       "requestUrl",
       "timeout"
    );
}


class BaMLVerbRecord extends BaMLVerb {
    public static $params = array(
        "requestUrl",
        "requestUrlTimeout",
        "terminatingDigits",
        "maxDuration",
        "transcribe",
        "transcribeCallbackUrl"
    );
}

class BaMLVerbGather extends BaMLVerb {
    public static $params = array(
        "requestUrl",
        "requestUrlTimeout",
        "terminatingDigits",
        "maxDigits",
        "interDigitTimeout",
        "bargeable"
    );
}
class BaMLVerbSendMessage extends BaMLVerb {
    public static $params = array(
        "from",
        "to",
        "requestUrl",
        "requestUrlTimeout",
        "statusCallbackUrl"
    );
}
class BaMLVerbHangup extends BaMLVerb {}

final class BaMLSpeakSentence extends BaMLVerbSpeakSentence {}
final class BaMLTransfer extends BaMLVerbTransfer {}
final class BaMLPlayAudio extends BaMLVerbPlayAudio {}
final class BaMLRedirect extends BaMLVerbRedirect {}
final class BaMLGather extends BaMLVerbGather {}
final class BaMLSendMessage extends BaMLVerbSendMessage {}
final class BaMLHangup extends BaMLVerbHangup {}
abstract class BaMLAssert {}

?>
