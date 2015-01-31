<?php
/**
 * @type Voice
 * https://catapult.inetwork.com/docs/api-docs/calls/
 *
 * A class for the listed
 * voices in Catapult
 * list of available voices
 * defined here: https://catapult.inetwork.com/docs/api-docs/calls/
 * 
 * 
 * TODO: merge voice with gender to  
 * prevent exception.
 */

namespace Catapult;

final class Voice extends Types {
	public static $available_voices = array(
		"Jorge" => "male",
		"Kate" => "female",
		"Susan" => "female",
		"Julie" => "female",
		"Dave" => "male",
		"Paul" => "male",
		"Bridget" => "male",
		"Violeta" => "female",
		"Jolie" => "female",
		"Bernard" => "male",
		"Katrin" => "female",
		"Stefan" => "male",
		"Paola" => "male",
		"Luca" => "male"
	);

	public $gender = "male";

	public function __construct($voice)
	{
		if (!(in_array($voice, array_keys(self::$available_voices))))
			throw new \CatapultApiException("Voice unrecognized");


		$this->voice = $voice;
		$this->gender = self::$available_voices[$voice];
	}

	public function __toString()
	{
		return (string) $this->voice;
	}

}
?>

