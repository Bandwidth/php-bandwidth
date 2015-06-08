<?php
/**
 * @class BaMLGeneric
 *
 * generic class for BaML
 * verbs, and containers
 * should provide functions to
 * control data in the objects
 */
namespace Catapult;

abstract class BaMLGeneric { 
    public function setText($text) { }
    public function addText($text) { }
    public function getText() {
        return (string) $this->text;
    }
}
