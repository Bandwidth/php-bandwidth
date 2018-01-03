<?php
/**
 * @class BaMLText
 *
 * Defines the innerText for BaMLVerbs
 */
namespace Catapult;
final class BaMLText extends BaMLAssert {
    public function __construct($text) {
        $this->text = $text;
    }
    public function __toString() {
        return $this->text;
    }
}
