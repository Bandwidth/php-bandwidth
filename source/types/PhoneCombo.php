<?php
/**
 * @type PhoneCombo
 *
 * A one-to-one phone number object
 * where the object should provide
 * from => Catapult\PhoneNumber
 * to => Catapult\PhoneNumber
 */
 
namespace Catapult;

final class PhoneCombo extends Types {
    public function Make($sender, $receiver)
    {
      return array( "from" => (string) $sender, "to" => (string) $receiver);
    }
}
