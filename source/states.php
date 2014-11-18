<?php
namespace Catapult;

interface MESSAGE_STATES {
	const received = "RECEIVED";
	const queued = "QUEUED";
	const sending = "SENDING";
	const sent = "SENT";
	const error = "ERROR";
}

interface CALL_STATES {
	const started = "STARTED";
	const rejected = "REJECTED";
	const active = "ACTIVE";
	const completed = "COMPLETED";
	const transferring = "TRANSFERRING";
}

interface RECORDING_STATES {
	const recording = "RECORDING";
	const complete = "COMPLETE";
	const saving = "SAVING";
	const error = "ERROR";	
}

interface CONFERENCE_STATES {
	const active = "ACTIVE";
	const created = "CREATED";
	const completed = "COMPLETED";
}

interface GATHER_STATES {
	const completed = "COMPLETED";
	const started = "STARTED";
}
?>
