<?php
namespace Catapult;

/**
 * types are a set of client side helpers
 * to ease the integration of warnings,
 * exceptions in a application. Unless 
 * specified these should merely serialize into
 * a string with __toString
 *
 * provides:
 * @class DTMF -- Generate valid DTMF's
 * @class TextMessage -- A valid textmessage in length and
 * only containts printable characters
 * @class PhoneNumber -- Valid phone numbers
 * @class CallBackURL -- A URL that is encoded with RFC 3896
 * @class ID -- a Catapult id where the prefix specified its type
 * @class Voice -- A valid voice in Catapult API
 * @class SIP -- A valid SIP url
 * @class Sentence -- Printable sentence
 * @class Size -- Catapult list size constraints
 * @class Date -- Catapult Formatted Dates
 * @class Parameters -- Generic Parameter Handler
 * @class FileHandler -- File Handling
 * @class Id -- Catapult IDs. With prefixes
 * @class CallCombo -- Call Combos
 * @class Timeout -- Catapult Compilant Timeout
 */

abstract class Types { 
  /**
   * All Types should supply is Valid
   * this should be able to called staticly 
   * or in object form. 
   *
   * TODO:
   * Add spport for other areas needing isValid/1
   */
  public function isValid() {
    return $this->perform(FALSE);
  }
} 
