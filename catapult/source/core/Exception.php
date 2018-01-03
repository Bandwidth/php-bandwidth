<?php

final class CatapultApiException extends \Exception
{
  public function __construct($result) {
    $this->result = $result;
    $code = 0;
    if (isset($result['error_code']) && is_int($result['error_code'])) {
      $code = $result['error_code'];
    }
    if (isset($result['error_description'])) {
      // OAuth 2.0 Draft 10 style
      $msg = $result['error_description'];
    } else if (isset($result['error']) && is_array($result['error'])) {
      // OAuth 2.0 Draft 00 style
      $msg = $result['error']['message'];
    } else if (isset($result['error_msg'])) {
      // Rest server style
      $msg = $result['error_msg'];
    } else {
      //$msg = 'Unknown Error. Check getResult()';
      $msg = $this->result;
    }

    parent::__construct($msg, $code);

    if (Catapult\Log::isOn()) {
      $trace = $this->getTrace();
      /** get the last line we got an error on, would be the user's file **/

      $line = $trace[sizeof($trace) - 1]['line'];
      Catapult\Log::write(time(), "line: " . $line, $this->result);
    }
  }

  public function getResult() {
    return $this->result;
  }

  public function getType() {
    if (isset($this->result['error'])) {
      $error = $this->result['error'];
      if (is_string($error)) {
        // OAuth 2.0 Draft 10 style
        return $error;
      } else if (is_array($error)) {
        // OAuth 2.0 Draft 00 style
        if (isset($error['type'])) {
          return $error['type'];
        }
      }
    }
    return 'Exception';
  }

  public function __toString() {
    $str = $this->getType() . ': ';
    if ($this->code != 0) {
      $str .= $this->code . ': ';
    }
    return $str . $this->message;
  }
}

/**
 * Catapult API Warning added 1/30/2015
 * to not stop execution when dealing
 * with minor errors. Things
 * that can arise like bad formed
 * input, this does not always need
 * to stop the program and should only 
 * show as warning
 */
class CatapultApiWarning {
    /** show output of what happened **/
    /** todo make CatapultApiWarning recognize global constants **/
    public function __construct($message) {
      $this->message = $message;
    }
    public function __toString() {
      return "CatapultWarning: " . $this->message . "\n";
    }
}
