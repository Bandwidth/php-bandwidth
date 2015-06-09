<?php
namespace Catapult;

interface MESSAGE_STATES {
  const received = "received";
  const queued = "queued";
  const sending = "sending";
  const sent = "sent";
  const error = "error";
}

interface MESSAGE_DIRECTIONS {
  const in = "in";
  const out = "out";
}

interface CALL_STATES {
  const started = "started";
  const rejected = "rejected";
  const active = "active";
  const completed = "completed";
  const transferring = "transferring";
}

interface CALL_ERROR {
  const CALL_REJECTED = "CALL_REJECTED";
  const UNSPECIFIED = "UNSPECIFIED";
  const NORMAL_CLEARING = "NORMAL_CLEARING";
  const USER_BUSY = "USER_BUSY";
  const SWITCH_CONGESTION = "SWITCH_CONGESTION";
  const NORMAL_CIRCUIT_CONGESTION = "NORMAL_CIRCUIT_CONGESTION";
}

// note 'state' in recording
// has been deprecated. Please
// use 'status'
// RECORDING_STATES becomes
// RECORDING_STATUSES
// 
interface RECORDING_STATUSES {
  const recording = "recording";
  const complete = "complete";
  const saving = "saving";
  const error = "error";
}

interface RECORDING_STATES {
  const recording = "recording";
  const completed = "completed";
  const saving = "saving";
  const error = "error";
}

interface CONFERENCE_STATES {
  const active = "active";
  const created = "created";
  const completed = "completed";
}

interface CONFERENCE_MEMBER_STATES {
  const active = "active";
  const completed = "completed";
}

interface CONFERENCE_SPEAK_STATES {
  const started = "started";
  const done = "done";
}

interface SPEAK_STATES {
  const started = "PLAYBACK_START";
  const stopped = "PLAYBACK_STOP";
}

interface GATHER_STATES {
  const completed = "completed";
  const started = "started";
}

interface GATHER_REASONS {
  const terminatingDigit = "terminating-digit";
  const interDigitTimeout = "inter-digit-timeout"; 
  const maxDigits = "max-digits";
}

interface PLAYBACK_STATES {
  const PLAYBACK_STARTED = "PLAYBACK_STARTED";
  const PLAYBACK_ENDED = "PLAYBACK_ENDED";
}

interface NUMBER_STATES {
  const enabled = "enabled";
  const released = "released";
  const available = "available";
}

interface TRANSCRIPTION_STATES {
  const transcribing = "transcribing";
  const complete = "complete";
  const error = "error";
}
