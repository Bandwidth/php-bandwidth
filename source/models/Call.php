<?php
/**
 * @model Call
 * http://ap.bandwidth.com/docs/rest-api/calls/
 *
 *
 * Provides call functionality
 */
namespace Catapult;

final class Call Extends AudioMixin {
      /**
       * construct the call as initiated  or new
       * each constructor must have a way to call itself's create/1 function with the
       * arguments
       *
       * init forms
       *
       * GET:
       * Call('call-id')
       * Call('conference-id', 'call-id')
       * Call()
       *
       * POST
       * Call('conference-id', array)
       * Call(array)
       *
       */
      public function __construct() {
        $data = Ensure::Input(func_get_args());
        parent::_init($data, new DependsResource,
          new LoadsResource(
            array("primary" => "GET", "id" => "id", "init" => array("conferenceId"), "silent" => false)
          ),
          new SchemaResource(
            array("fields" => array(
            'id', 'from', 'to', 'recordingEnabled', 'recordingFileFormat', 'callbackUrl', 'events',
            'direction', 'callbackHttpMethod', 'state', 'startTime', 'endTime', 'activeTime', 'bridgeId'
            ), "needs" => array("id", "direction", "from", "to"))
          ),
          new SubFunctionResource(
              array(
                array("term" => "transcriptions", "type" => "get"),
                array("term" => "recordings", "type" => "get"),
                array("term" => "events", "type" => "get"),
                )
            )
        );
      }

    /**
     * Transfer a call
     * Call object MUST already be initialized
     * or created given a Legal call id
     * if we dont have a call id throw warning
     *
     * @param phone: phone number
     * @param transfer_caller_id: A Phone number
     */
    public function transfer($phone, $args = array() /* polymorphic */)
    {
      $url = URIResource::Make($this->path, array($this->id));
      $data = Ensure::Input($args);
      $data->add("transferTo", (string) $phone);
      $data->add("state", CALL_STATES::transferring);

      $response = $this->client->post($url, $data->get());
      
      $id = Locator::Find($response);

      $data->add("id", $id);

      return Constructor::Make($this, $data->get());
    }

    /**
     * Bridge calls
     * forward to object bridge
     * 
     * @param calls: list of calls
     * @param args: additional data to pass
     */
    public static function bridge($calls, $args)
    {
      return Bridge::Create($calls, $args);
    }

	/**
     * Bridge this call with another call
     * forward to object bridge
     *
     * @param calls: call to bridge with
     * @param args: additional data to pass
     */
    public function bridgeWith($call, $args)
    {
      return self::bridge(array($this, $call), $args);
    }

    /**
     * Refresh a call id
     * where the call id MUST
     * be initiated like transfer
       * stub to create
       * @return void
     */ 
    public function refresh()
    {
      $this->create(PhoneCombo::Make(new PhoneNumber($this->from), new PhoneNumber($this->to)));
    }

    /**
     * Hangup a call
     *
     * needs a call id
     * @return void
     */
    public function hangup()
    {
      $url = URIResource::Make($this->path, array($this->id));
      $data = new DataPacket(array("state" => CALL_STATES::completed));
      $this->client->post($url, $data->get());

      return Constructor::Make($this, $data->get());
    }

    /**
    * Accept an incoming
    * call.
    *
    * @return void
    */
    public function accept()
    {
      $url = URIResource::Make($this->path, array($this->id));
      $data = new DataPacket(array("state" => CALL_STATES::active));
      $id = Locator::Find($this->client->post($url, $data->get()));
      $data->add("id", $id);

      return Constructor::Make($this, $data->get());
    } 

    /**
     * Reject an incoming call. Call id must already be passed
     *
     * @return void
     */
    public function reject()
    {
      $url = URIResource::Make($this->path, array($this->id));
      $data = new DataPacket(array("state" => CALL_STATES::rejected));
      $this->client->post($url, $data->get());

      return Constructor::Make($this, $data->get());
    }

   /**
    * Sends a string of characters as DTMF on the given call_id
    * Valid chars are '0123456789*#ABCD'
    *
    * @param dtmf: dtmf characters 
    */
    public function sendDtmf($dtmf)
    {
      $args = Ensure::Input($dtmf);
      $dtmf = $args->get();
      $url = URIResource::Make($this->path, array($this->id, "dtmf"));
      $data = new DataPacket(array("dtmfOut" => (string) $dtmf));

      $this->client->post($url, $data->get());
    }

    /**
     * wait for a call to go to any
     * state other than 'started'
     * @timeout a default time to wait
     * THIS WILL BE DEPRECATED 
     */
    public function wait($timeout=null)
    {
        $delta = time();
        if ($timeout == null)
            $timeout = 60 * 2; // two minutes
        if (!($this->check("state", "started")))
          Throw new \CatapultApiException("Call already in non 'started' state'");
        while (true) {
          if (!($this->check("state", "started")))
            break;
          if ((time() - $delta) > $timeout)
            break;
        }
    }

    /**
     * Forward to gather
     * object and return
     *
     * @return Gather object with loaded call id and client
     */
    public function gather()
    {
      return new Gather(array("callId" => $this->id));
    }

    /**
     * overloads existing
     * get_audio_url/1
     */
    public function getAudioUrl()
    {
      return URIResource::Make($this->path, array($this->id, "audio"));
    }
	
}
