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
    * @param args: EnsuredInput
    * @param class: Intiated model class
    */
    public function _init($args, $class) 
    {

      $this->model = $class;
      $args = $args->get();	
      $args = Cleaner::Omit($args);

      $this->model->id = $args['id'];
      $this->model->get();
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
      return $this->model->$prop;
    }
}

?>
