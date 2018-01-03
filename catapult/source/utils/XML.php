<?php
namespace Catapult;

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
     * render using the default encoding
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
      if ($baml && !in_array($tag, BaMLVerb::$valid)) {
        throw new \CatapultApiException("This is not a valid baML verb");
      }

      return "<$tag";
    }

    /**
     * a full open tag
     * for the BaML document
     * optionally checks if its valid
     */
    public static function fullOpen($tag, $baml=false) {
      if ($baml && !in_array($tag, BaMLVerb::$valid)) {
        throw new \CatapultApiException("This is not a valid baML verb");
      }

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
