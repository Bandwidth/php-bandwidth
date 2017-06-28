<?php
/**
 * @type TextMessage
 * Aux functions to make sure text fits 
 * warn by default, when off take out the extranous text pieces 
 */

namespace Catapult;
final class TextMessage extends Types {
    public function __construct($message='', $warn=TRUE)
    {
      if ($warn && strlen($message) > 2048) {
        throw new \CatapultApiException("Text message was too long. use: warn[FALSE] to omit. Text: " . $message);
      }

      $this->message = $message;
    }
    public function __toString()
    {
      return strlen($this->message) >= 2048 ? (substr($this->message, 0, 2048)) : $this->message;
    }
} 
