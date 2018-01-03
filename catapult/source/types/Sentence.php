<?php
/**
 * @type Sentence
 *
 * A sentence ready data
 * structure.
 * remove all unprintable characters
 */

namespace Catapult;
final class Sentence extends Types {
    /**
     * Form the sentence
     * object version
     */
    public function __construct($sentence)
    {
      $this->sentence = $sentence;
    }

    /**
     * Form a sentence. Only
     * allow printable characters for
     * percision. 
     * 
     * @param sentence: sentence to speak
     * @param singular return as array or scalar
     */
    public function Make($sentence, $singular=false)
    {
      if (!($singular)) {
        return array(
          "sentence" => $sentence
        );
      }

      return $sentence;
    }

    public function __toString()
    {
      return $this->Make($this->sentence, TRUE);
    }
}
