<?php
namespace Catapult;
/**
 * Standard Phone Number Validation. 
 * Numbers need to be in E.164 format
 */
final class PhoneNumber extends Types {
	public function __construct($number)
	{
		$m = array();
		preg_match("/^([0-9\(\)\/\+ \-]*)$/", $number, $m);

		if (!(sizeof($m) > 0))
			throw new \CatapultApiException("Invalid phone number inputed: " . $number);
		
		$this->number = $number;
	}

	public function Make($args)
	{
		return;
	}

	public function __toString()
	{
		return (string) $this->number;
	}
}

?>
