<?php
namespace Catapult;

/* Audio mixin support through Bandwidth.com
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

	public function __construct()
	{
		$this->client = Client::get();
	}

	public function play_audio($args = array() /* polymorphic */)
	{

		$this->client = Client::get();
		$args = Ensure::Input($args);
		
		$url = new URIResource($this->get_audio_url());
	
		$data = $args->get();	

		$this->client->post((string) $url, $data);
	}	

	public function stop_audio()
	{
		$this->client = Client::get();

		$url = new URIResource($this->get_audio_url()); 
		$data = new DataPacket(array("fileUrl"=> ""));

		$this->client->post((string) $url, $data->get());
	}

	public function speak_sentence($args /* polymorphic */)
	{
		$this->client = Client::get();
		$data = Ensure::Input($args);

		$url = new URIResource($this->get_audio_url());	

		$this->client->post((string) $url, $data->get());		
	}

	public function stop_sentence()
	{
		$this->client = Client::get();

		$url = new URIResource($this->get_audio_url());
		$data = new DataPacket(array("sentence" => ""));

		$this->client->post($url, $data->get());
	}

	public function get_audio_url()
	{ }
}


?>
