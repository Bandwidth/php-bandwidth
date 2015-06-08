<?php
/**
 * @class ConferenceEvent
 *
 * https://catapult.inetwork.com/docs/callback-events/
 *
 * Conference event. Provide base
 * functionality to edit a conference  from
 * the event. By default the conference's event 
 * should also know its calls
 *
 * ConferenceEvents include:
 * conferences,
 * conference members,
 * conference speak events 
 */

namespace Catapult;

class ConferenceEventMixin extends EventType {
    public function __construct()
    {
      $data = Ensure::Input(func_get_args(),Converter::toArray(json_decode("php://input")));

      return parent::_init($data, new Conference);
    }
}
