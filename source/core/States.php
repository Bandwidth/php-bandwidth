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

interface NUMBER_STATES {
	const enabled = "ENABLED";
	const released = "RELEASED";
	const available = "AVAILABLE";
}

interface TRANSCRIPTION_STATES {
    const transcribing = "TRANSCRIBING";
    const complete = "COMPLETE";
    const error = "ERROR";
}

interface Events {
	const CALL_REJECTED = 1;
	const UNSPECIFIED = 2;
	const NORMAL_CLEARING = 3;
	const USER_BUSY = 4;
	const NORMAL_UNSPECIFIED = 5;
	const NORMAL_CIRCUIT_CONGESTION = 6;
	const SWITCH_CONGESTION = 7;
}



?>
