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
  public function playAudio(/* polymorphic */)
  {
    $args = Ensure::Input(func_get_args());
    $url = new URIResource($this->getAudioUrl());
    $data = $args->get(); 

    /** update add simplicity. playAudio('audioFile') = playAudio(array('audioFile' => 'audioFile')) **/
    if ($args->is_string()) {
      $data = array(
        "fileUrl" => $data
      );
    }

    $this->client->post((string) $url, $data);
  } 

  /**
   * Stops the audio
   *
   */
  public function stopAudio()
  {
    $url = new URIResource($this->getAudioUrl()); 
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
  public function speakSentence($args /* polymorphic */)
  {
    $args = Ensure::Input($args);
    $url = new URIResource($this->getAudioUrl()); 
    $data = $args->get();

    $this->client->post((string) $url, $data);    
  }

  /**
   * Stops a sentence
   *
   */
  public function stopSentence()
  {
    $url = new URIResource($this->getAudioUrl());
    $data = new DataPacket(array("sentence" => ""));

    $this->client->post($url, $data->get());
  }

  /**
   * Defined in inherited classes.
   */
  public function getAudioUrl()
  { }
}
