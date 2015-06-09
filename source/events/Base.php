<?php

/**
 * Base of Catapult events. 
 * All functionality here should reference 
 * catapult models. For more information on Catapult's
 * events, https://catapult.inetwork.com/docs/callback-events/
 */
namespace Catapult;

/**
 * Primary event object. All events
 * should provide its basic
 * methods. Moreover be initialized
 * by it.  
 */
class Event {
    public function __construct($data=null) 
    {
      if (!(in_array($_SERVER['HTTP_METHOD'], array("GET", "POST"))))
        throw new \CatapultApiException("Catapult events only accept GET and POST");


      if ($data!=null) {// mocking tests only
       $data = Ensure::Input($data);
      } else {
        $data = Ensure::Input(json_decode(file_get_contents("php://input")));
      }

      return new EventType(
      new EventResource($this, $data)
      );

    }
    public function __call($function, $args) {
      return $this->model->$function($args);
    }
    public function __get($key) {
      return $this->model->$key;
    }
}


class EventAssert extends Event {

  public static $events = array(
    "CallEvent" => array(
      "incomingcall", 
      "answer", 
      "reject", 
      "timeout", 
      "error", 
      "speak", 
      "dtmf", 
      "recording", 
      "playback", 
      "transcription", 
      "hangup"
    ), 
    "AnswerCallEvent" => array(
      "answer"
    ), 
    "IncomingCallEvent" => array(
      "incomingcall"
    ),
    "ErrorCallEvent" => array(
      "error"
    ), 
    "PlaybackCallEvent" => array(
      "playback"
    ), 
    "SpeakCallEvent" => array(
      "speak"
    ), 
    "HangupCallEvent" => array(
      "hangup"
    ), 
    "RejectCallEvent" => array(
      "reject"
    ), 
    "MessageEvent" => array(
      "sms"
    ), 
    "GatherCallEvent" => array(
      "gather"
    ), 
    "DtmfCallEvent" => array(
      "dtmf"
    ), 
    "RecordingCallEvent" => array(
      "recording"
    ), 
    "TranscriptionCallEvent" => array(
      "transcription"
    ), 
    "ConferenceEvent" => array(
      "conference", 
      "conference-member", 
      "confeence-playback"
    ), 
    "ConferenceMemberEvent" => array(
      "conference-member",
      "conference-speak",
      "conference-playback"
    ), 
    "ConferenceMemberSpeakEvent" => array(
      "conference-speak"
    ), 
    "ConferenceMemberPlaybackEvent" => array(
      "conference-playback"
    )
  );

  /**
   * check if the right event
   * has been called this
   * is done by looking at 
   * what the keywords  have 
   * for this eventType
   */
  public static function check($class, $eventType) 
  {
    $class = preg_replace("/Catapult\\\/", "", get_class($class));

    /** does the event exist ? **/
    if (array_key_exists($class, self::$events)) {

      if (in_array($eventType, self::$events[$class])) {
        return true;
      }
    }

    return false;
  }
}

/**
 * A generic to handle all event types. Afterwards
 * it should initiate the type specified in datapacket
 * 
 * EventType($data) where data
 *
 * i.e
 * data:
 * {
 *    "eventType": "sms"   
 * }
 * makes MessageEvent
 * 
 */
class EventType extends Event {
    public function __construct($resource=NULL)
    { 
      /**
      * when we're called directly as 
      * a sub class we figure out our input first.

      * i.e
      * event = new Event;
      *
      * this is only when the user doesn't knows which
      * event should be triggered in his program.
      * or when multiple events lead to the same script
      *
      */
    }

    /**
    * TODO:
    * models provide stringify functionality
    * which resembles:
    *
    * CLASS(op1, op2, op3)
    *
    * --- event information should be included 
    */
    public function __toString()
    {
      return (string) $this->model;
    }


   /**
    * _init will register things
    * used by this event object
    *
    * @param proto: EnsuredInput. needs to be two arity
    * @param class: Intiated model class
    */
    public function _init($proto, $class = null) 
    {
      $this->active = false;
      $this->model = $class;
      $cons = $proto[0]->get();	
      $args = $proto[1]->get();
      /** make sure our constraints match **/

      foreach ($cons as $k => $v) {
        /** 
         * one constraint does not match
         * when this happens we need
         * to revoke the event
         *
         * i.e
         * MessageEvent(array("direction" => "incoming"))
         *
         * should not accept outgoing
         */
        if (isset($args[$k]) 
           && $args[$k] !== $v) {
          return;
        }
      }

      /**
       * now that our constraints match
       * make sure 'eventType' is this classes
       * type. 
       */

      if (!($this->isInvoked($args))) {
        return;
      }

      /** set the variables **/

      foreach ($args as $k => $v) {
        $this->$k = $v;
      }

      /**
       * TODO move
       *
       * as of now model will not autmatically
       * be made.
       */
      //$this->model->id = $args['id'];
      //$this->model->get();
    }


    public function isInvoked($args)
    {
      if (EventAssert::check($this, $args['eventType'])) {
        $this->active = true;
      }

      return $this->active;
    } 


    /**
     * isActive/1 tells us if the event
     * is being used or not. This
     * will make all other events 
     * non functional
     */
    public function isActive()
    {
      return $this->active;
    }

    /**
     * calling the event should be
     * the same as using isActive/0, 
     * as a result: 
     *
     * answerCallEvent->isActive() = answerCallEvent
     */
    public function __invoke() {
      return $this->isActive();
    }

   /**
    * Event calls should be forwaded
    * to their models
    *
    * This will be identical to 
    * Call->create
    * where create is any function of the public API
    *
    * @param function: model function
    * @param args: args for function
    */	
    public function __call($function, $args)
    {
      /**
      * Handling will be done
      * by GenericResource
      */
      return $this->model->$function($args);
    }
    /**
    * Same for properties all should
    * be identical
    *
    * @param prop: property for object
    */
    public function __get($prop)
    {
      if (isset($this->$prop)) {
        return $this->$prop;
      }
      return $this->model->$prop;
    }
}
