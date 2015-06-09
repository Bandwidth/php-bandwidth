<?php

namespace Catapult;
/**
 * EventResource. This should
 * include all the information for each
 * event type and how to form its model
 * 
 *
 */

final class EventResource extends BaseResource {
    /**
     * EventResource type can be one of the following
     *
     * - Calls
     * - Conferences
     * - Messages
     * - Recordings
     * 
     */
    public function __construct(&$object, $data) {
      $data = Ensure::Input($data);
      $args = Cleaner::Omit($data->get());
      $type = $args['eventType'];
      $splits = explode("-", $type);
      $class = __CLASS__;


      $object->eventType = $type;

      /**
      * when we're called directly as 
      * a sub class we should get our input first.
      *
      * i.e
      * call_event = new CallEvent;
      *
      * this is only when the user knows which
      * event should be triggered in his program.
      */
      /** conferences use hyphens **/	
      /** i.e speak-conference **/
      if (sizeof($splits)>1) {
        $g = "";
      foreach($splits as $s) {
        $g .= ucwords($g);
      }
        $class = "Catapult\\" . "Conference" . $g . "Event";
        return $object->model = new Conference($args['id']);
      }
      /** sms is singular and does not use any other term, use Message here **/
      if ($type == "sms") {
        return $object->model = new Message($args['id']);
      }
      if (in_array($type, array("incoming", "hangup", "answer", "speak", "recording", "dtmf", "gather"))) {
        $cl =  "Catapult\\" . ucwords($type) . "CallEvent";
        return $object->model = new Call($args['id']);
      }	

      throw new \CatapultApiException("EventType was not found in list of events");
    }
  }
