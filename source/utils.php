<?php
namespace Catapult;

/**
 * Base functions
 * for dealing with
 * responses, headers
 * content and internal type
 * handling
 */
class BaseUtilities { 
    public static function is_multidimensional($array)
    {
          $rv = array_filter($array,'is_array');
          if(count($rv)==sizeof($array)) return true;

          return false;

    }
    public static function camelcase()
    {
          $c = "";

          foreach ($array as $a)	
              $c .= ucwords($a);

          return $c;

    }
	public static function find($headers, $term)
	{
		if (!(isset($headers[$term])))
			throw new \CatapultApiException("No header found as $term:");

		return $headers[$term];
	}

    public static function same_type(&$var1, &$var2){
        return gettype($var1) === gettype($var2);
    }

    public static function is_ref(&$var1, &$var2) {
        //If a reference exists, the type IS the same
        if(!self::same_type($var1, $var2)) {
            return false;
        }

        $same = false;

        if(is_array($var1)) {
           do {
               $key = uniqid("is_ref_", true);
           } while(array_key_exists($key, $var1));

           //The two variables differ in content ... They can't be the same
           if(array_key_exists($key, $var2)) {
               return false;
           }

           //The arrays point to the same data if changes are reflected in $var2
           $data = uniqid("is_ref_data_", true);
           $var1[$key] =& $data;
           //There seems to be a modification ...
           if(array_key_exists($key, $var2)) {
                if($var2[$key] === $data) {
                     $same = true;
                }
            }

            //Undo our changes ...
            unset($var1[$key]);
          } elseif(is_object($var1)) {
              //The same objects are required to have equal class names ;-)
              if(get_class($var1) !== get_class($var2)) {
                 return false;
              }

              $obj1 = array_keys(get_object_vars($var1));
              $obj2 = array_keys(get_object_vars($var2));

              //Look for an unused index in $var1
              do {
                 $key = uniqid("is_ref_", true);
              } while(in_array($key, $obj1));

              //The two variables differ in content ... They can't be the same
              if(in_array($key, $obj2)) {
                  return false;
              }

              //The arrays point to the same data if changes are reflected in $var2
              $data = uniqid("is_ref_data_", true);
              $var1->$key =& $data;
              //There seems to be a modification ...
              if(isset($var2->$key)) {
                  if($var2->$key === $data) {
                     $same = true;
                  }
              }

              //Undo our changes ...
              unset($var1->$key);
            } elseif (is_resource($var1)) {
                if(get_resource_type($var1) !== get_resource_type($var2)) {
                    return false;
                }

                return ((string) $var1) === ((string) $var2);
            } else {
                //Simple variables ...
                if($var1!==$var2) {
                    //Data mismatch ... They can't be the same ...
                    return false;
                }

                //To check for a reference of a variable with simple type
                //simply store its old value and check against modifications of the second variable ;-)

                do {
                    $key = uniqid("is_ref_", true);
                } while($key === $var1);

                $tmp = $var1; //WE NEED A COPY HERE!!!
                $var1 = $key; //Set var1 to the value of $key (copy)
                $same = $var1 === $var2; //Check if $var2 was modified too ...
                $var1 = $tmp; //Undo our changes ...
            }

            return $same;
       }
}

/**
 * Helpers for the response
 * provide convinience functions
 * to search headers, body, code
 * for needed information
 * locator should check
 * Location:
 * for a url
 */
class Locator extends BaseUtilities {
	/* Specialization for
	 * location: 
	 * header. 
	 * either return the full url
	 * or qualified id which is found
         * as the last directory seperated
         * entity
	 * @param $headers -> string based header string
 	 */
	public static function find($headers,$id=true)
	{
		$header = parent::find($headers, "Location");

		if ($id) {
			$match = array();

			$pieces = explode("/", $header);

			return str_replace("\r", "", str_replace("\n", "", $pieces[sizeof($pieces) - 1]));
		}
		
		return $header;
	}	
}


/**
 * Given a context and command to the api
 * take out any unneeded information
 * before passing it back for a request
 * additionally make sure all legal parameters
 * have been passed
 */
class Prepare extends BaseUtilities {
	/* function Input/1 
	 * should either throw
	 * an error or erase
	 * unneeded arguments
	 * this will vary depending on the
	 * commands strictness
         * @param context -> context
	 * @param data -> data
	 * @param subcotext -> call
	 * @param strict -> validate parameters
	 */
	public function Input($context, $data, $subcontext="all", $strict=FALSE)
	{
		$valid = $context::$valid_opts;

	}

	public function Output($context, $data, $subcontext="all", $strict=FALSE)
	{
		return $this->Input($context, $data, $subcontext, $strict);
	}

}

final class Converter extends BaseUtilities {
	/* in some cases we need to
	 * convert a flat json
	 * object into its array
	 * form. This prevents
	 * extra overhead
	 * in later seles
	 *
	 * @param json: one layer json object
	 */
	public function ToArray($json)
	{
		return get_object_vars($json);
	}
}



final class XMLUtility extends BaseUtilities {

    /**
     * Basic options for
     * XML utility. XML
     * document encoding 
     * and other options
     * should be set from setOption/1
     */
    public static $options = array(
        "header" => '<?xml version="1.0" encoding="UTF-8"?>',
        "encoding" => "UTF-8"
    );

    /**
     * Takes a struct of 
     * one dimensional XML
     * elements and makes
     * them a BaML tree
     *
     * Provided object must
     * have its validating method available
     * @param XML struct
     * @returns BaMLResource
     */
    public function Make(&$baml, $values=null) {
        $stack = array();
        $elements = array();

        if ($values)
            $baml->values = $values;

        foreach ($baml->values as $el) {
            $idx = count($elements);
            if ($el['type'] == "complete" || $el['type'] == "open") {
                $bobj = $baml->register($el);
                $elements[$idx] = $bobj;
    
                if ($el['type'] == "open") {
                    $stack[count($stack)] = &$elements;
                    $texts[$bobj->level] = &$elements[$idx]->text;
                    $elements = &$elements[$idx]->verbs;
                }
            }
            if ($el['type'] == 'cdata') {
                $texts[$el['level']] .= trim($el['value']);
            }
            if ($el['type'] == "close") {
                $elements = &$stack[count($stack) - 1];
                unset($stack[count($stack) - 1]);
            }
        }

        unset($baml->values);

        $baml->data = $elements;

        return $elements;
    }

    /**
     * make without baML
     * schema. Really just used for tests
     *
     */
    public function MakePred($xml) {
        $baml = new BaML;
        $baml->values = $xml;

        self::make($baml);
    }

    /**
     * Default header for baML
     * will set encoding based
     * on XMLUtility static class
     *
     *
     */
    public static function getHeader() {
        return self::$options['header']; 
    }

    /**
     * default open
     * for baML verb
     * 
     */
    public static function open($tag, $baml=false) {
        if ($baml && !in_array($tag, BaMLVerb::$valid))
            throw new \CatapultApiException("This is not a valid baML verb");

        return "<$tag";
    }

    /**
     * a full open tag
     * for the BaML document
     * optionally checks if its valid
     */
    public static function fullOpen($tag, $baml=false) {
        if ($baml && !in_array($tag, BaMLVerb::$valid))
            throw new \CatapultApiException("This is not a valid baML verb");

        return "<$tag>";
    }

    /**
     * a close tag
     * for the BaML document
     * optionally checks if its valid
     */
    public static function close($tag, $baml=false) {
        return "</$tag>";
    }

    /** 
     * a full close tag
     * for BaML
     * optionally checks if its valid
     */
    public static function fullClose($tag, $baml=false) {
        return "</$tag>";
    } 

    /**
     * gets the attribute collection as a
     * string
     * @param attributes: BaMLAttributes [array]
     */
    public static function getAttributesCollection($attributes, $padding=1) {
        /** one space to seperate namespace **/
        $attrs = str_repeat(" ", $padding);
        foreach ($attributes as $attr) {
            $attrs .= $attr->getKey() . "=" . '"' . $attr->getValue() . '" ';
        }

        return substr($attrs, 0, strlen($attrs) - 1);
    }

    /**
     * simple xml join tree
     * sax wrapper around PHP's XML parser does
     * not provide
     * @param verbs: BaMLVerbs [array]
     */
    public static function joinTree($verbs) {
        $str = '';

        if (sizeof($verbs) > 0) {

           foreach ($verbs as $v) {
              $str .= self::openTag($v->getName(), $v->getAttributes());

              if ($v->hasVerbs()) {
                  $str .= self::joinTree($v->getVerbs());    
              }

              $str .= $v->getText();


              $str .= self::closeTag($v->getName());
           }
        }

        return $str;
    }

    /**
     * open tag for a BaML
     * document. will join
     * attributes if available
     *
     * @param name: valid BaML verb
     */
    public function openTag($name, $attributes=null) {
        if ($attributes) {
            $initial = $name;

            $name = self::open($initial);

            $name .= self::getAttributesCollection($attributes);

            $name .= ">";

            return $name;
         } 

         return self::fullOpen($name);
    }

    /**
     * close tag for
     * BaML document. Stub, see
     * full close
     */
    public function closeTag($name) {
         return self::fullClose("$name");
    }

    /**
     * Pretty print the XML 
     * document. Preserve spacing
     * tab at every newline
     * 
     * @param xml: xml document 
     */
    public static function indent($xml) {
        $cnt = 0;
        $d = 0;
        $t = 0;
        $c = 0;
        while ($cnt != strlen($xml)) {
            $ch = substr($xml, $cnt, 1);
            $ch2 = substr($xml, $cnt, 2);
            $lch = substr($xml, strlen($xml) - 1, 1);
            $f = strlen($xml);
            $a = strlen($xml);

            if ($ch == "<" && $ch2 != "</" && $cnt != "0") {
                $n = "\n";
                for($i = 0; $i <= ($t - $c); $i ++) {
                    $n .= "\t";
                }

                $pre = substr($xml, 0, $cnt);
                $af = substr($xml, $cnt, (max(strlen($xml), $cnt) - min(strlen($xml), $cnt)));
                $xml = $pre . $n . $af;

                $a = strlen($xml);
                $d ++;
                $t ++;
            }

            if ($ch2 == "</") {
                $n = "\n";
                for($i = 0; $i <= ($t - $c) - 1; $i ++) {
                    $n .= "\t";
                }

                $pre = substr($xml, 0, $cnt);
                $af = substr($xml, $cnt, (max(strlen($xml), $cnt) - min(strlen($xml), $cnt)));
                $xml = $pre . $n . $af;

                $a = strlen($xml);
                $c ++;
            }

            $cnt += ($a - $f) > 0 ? ($a-$f)+1:1;         
        }

        return $xml;
    }

    /**
     * parse a singular element
     * and return its pieces as either
     * an attribute, verb or text
     *
     * @param element: XML element
     */
    public static function parse($element) {
        $space = strpos(" ", $element);
        $tag = substr($element, 1, $space);
        $verb = "Catapult\\BaML" . $tag;

        return new $verb; 
    }
}

/* Provided a set of keywords 
 * take them out of the array
 * this is for events when they come
 * with 'callId', or 'conferenceId'
 * keyword we need to take out the keyword
 * afterwards lowercase the key
 */
final class Cleaner extends BaseUtilities {
	public static $keywords = array(
		"call", "conference", "message"
	);
	/* omits the keyword from
	 * the provided dataset
	 * where the dataset is a single
	 * dimensional array. New keywords without
         * are undercased
	 * @param data 
	 */
	public function Omit($data)
	{
		foreach ($data as $k => $d) {
			foreach (self::$keywords as $key) {
				if (preg_match("/^$key.*$/", $k, $m)) {
					$nk = strtolower(preg_replace("/$key/", "", $k));
					$data[$nk] = $d;
					unset($data[$k]);
				}
			}
		}
				
		return $data;
	}
}

?>
