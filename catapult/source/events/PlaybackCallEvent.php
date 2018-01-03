<?php
/**
 * @class PlaybackCallEvent
 * https://catapult.inetwork.com/docs/callback-events/audio-playback-event/
 *
 * Event type for Playbacks in calls.
 */
namespace Catapult;

final class PlaybackCallEvent extends EventType {
    public function __construct() {
      $data = Ensure::Input(func_get_args(), Converter::toArray(json_decode(file_get_contents("php://input"))));

      parent::_init($data, new Call);
    }
}
