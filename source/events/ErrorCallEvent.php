<?php
/**
 * @class ErrorCallEvent
 *
 * Provides information on Call Errors
 */

namespace Catapult;
final class ErrorCallEvent extends EventType {
    public function __construct() {
      $data = Ensure::Input(json_decode(file_get_contents("php://input")));

      parent::_init($data, new Call);
    }
}
?>
