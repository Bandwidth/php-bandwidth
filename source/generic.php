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
 */
class AudioMixin extends GenericResource { 
	private $client;

	/**
         * Construct the AudioMixin
	 * Usually client is already present
 	 * if not. Fetch the current
	 *
         */
	public function __construct()
	{
		if ($this->client == null)
			$this->client = Client::get();
	}

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

		$this->client = Client::get();
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
		$this->client = Client::get();

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
		$this->client = Client::get();
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
		$this->client = Client::get();

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
