<?php
/**
 * @class ConferenceMemberEvent
 *
 * https://catapult.inetwork.com/docs/callback-events/conference-member-event/
 *
 * Provide access to member events. Where member events
 * can be one of the following:
 * - Members Joining
 * - Member has been muted
 * - Member has left
 *
 * All of these should provide the same functionality, to update
 * the members information.
 */
namespace Catapult;

final class ConferenceMemberEvent extends ConferenceEventMixin {
	public function __construct() {
		return new ConferenceMember(func_get_args());
	}
}
?>
