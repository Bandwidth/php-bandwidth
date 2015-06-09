<?php
/** 
 * @type CallCombo
 *
 * created to provide
 * simple, 'from' and 'to' arrays
 */
namespace Catapult;
/**
 *
 * CallCombo should be accessible
 * by bridge. This is conviniece
 * and be used instead of passing each
 * call individualy
 */
final class CallCombo extends Types {
  public function Make($args /* polymorphic */)
	{
    $calls = func_get_args();
    $call_ids = array();

    foreach ($calls as $call) {
      if (is_object($call)) {
        $call_ids[] = $call->id;
      } else {
        $call_ids[] = $call;
      }
    }

    return $call_ids;
	}
  public function __toString() {
    return implode(",", $this->$call_ids);
  }
}
