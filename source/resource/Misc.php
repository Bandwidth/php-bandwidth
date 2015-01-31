<?php
namespace Catapult;

/**
 * Audio mixin support through Bandwidth.com
 * Catapult API. This should provide functions
 * to stop, start, and playing given audio
 * 
 *
 * All classes that supply: playAudio, stopAudio,
 * stopSentence, startSentence should inherit
 * this class.
 *
 * this needs to be refactored.
 */
abstract class AudioMixin extends GenericResource { 
	/**
     * Plays audio on the given
 	 * context provided a 'fileUrl'
	 * in args. If not present. Throw
 	 * warning
     *
     * @param args [assoc array] (needs joinUrl)
	 */
	public function play_audio($args = array() /* polymorphic */)
	{
		$args = Ensure::Input($args);
		
		$url = new URIResource($this->get_audio_url());
	
		$data = $args->get();	

		$this->client->post((string) $url, $data);
	}	

	/**
	 * Stops the audio
	 *
	 */
	public function stop_audio()
	{
		$url = new URIResource($this->get_audio_url()); 
		$data = new DataPacket(array("fileUrl"=> ""));

		$this->client->post((string) $url, $data->get());
	}

	/**
	 * Speak a sentence.
	 * where voice in args has to be a 
	 * valid voice
	 * 
	 * @param args [assoc array]
	 */
	public function speak_sentence($args /* polymorphic */)
	{
		$data = Ensure::Input($args);

		$url = new URIResource($this->get_audio_url());	

		$this->client->post((string) $url, $data->get());		
	}

	/**
	 * Stops a sentence
	 *
	 */
	public function stop_sentence()
	{
		$url = new URIResource($this->get_audio_url());
		$data = new DataPacket(array("sentence" => ""));

		$this->client->post($url, $data->get());
	}

	/**
	 * Defined in inherited classes.
	 */
	public function get_audio_url()
	{ }
}


?>
