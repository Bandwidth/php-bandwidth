<?php
namespace Catapult;

/** these will be used across the unit tests, they have no meaning in the api **/
define('__DEFAULT_SENDER__', '+14244443192');
define('__DEFAULT_RECEIVER__', '+14244443192');
define('__DEFAULT_CALLER_ID__', '+14244443192');
define('__DEFAULT_URL__', 'http://bandwidth.com/');
define('__DEFAULT_LOG_PREFIX__', 'catapult_');
define('__DEFAULT_LOG_PATH__', __DIR__ . DIRECTORY_SEPARATOR . "logs");
define('__DEFAULT_LOG_USER_PATH__', getcwd() . DIRECTORY_SEPARATOR . "logs");
define('__APPLICATION_UNIT_TEST__', __DIR__ . DIRECTORY_SEPARATOR);
define('__MEDIA_UNIT_TEST_FILE__', getcwd() . DIRECTORY_SEPARATOR . "files" . DIRECTORY_SEPARATOR . "catapult.jpg");
define('__MEDIA_UNIT_TEST_FILE_LOCATION__', getcwd() . DIRECTORY_SEPARATOR . "files");
define('__BAML_UNIT_TEST_FILE_LOCATION__', getcwd() . DIRECTORY_SEPARATOR . "files" . DIRECTORY_SEPARATOR . "test.xml");

/** runtime settings for debug / production release **/
interface API_MODE {
	const API_IN_DEBUG_MODE = 1;
}

/* do NOT change these. These will be exchanged at runtime */
interface API {
  const API_ENDPOINT = "https://api.catapult.inetwork.com";
  const API_VERSION = "v1";
  const APPLICATION_JSON = "application/json"; 
  const API_METHOD_POST = "POST";
  const API_METHOD_DEL = "DELETE";
  const API_METHOD_PUT = "PUT";
  const API_METHOD_GET = "GET";
  const API_DATE_FORMAT = "Y-m-d\TH:i:s\Z";
  const API_BANDWIDTH_USER_ID = "BANDWIDTH_USER_ID";
  const API_BANDWIDTH_TOKEN = "BANDWIDTH_API_TOKEN";
  const API_BANDWIDTH_SECRET = "BANDWIDTH_API_SECRET";
  const API_BANDWIDTH_APPLICATION_ID = "BANDWIDTH_APPLICATION_ID";
  const API_BANDWIDTH_VALID_NUMBERS = "BANDWIDTH_API_VALID_NUMBERS";
  const API_DEFAULT_APPLICATION = "APP-XX";
  const SDK_VERSION = "0.1";
  const SDK_USER_AGENT = "catapult-sdk-php";
  const SDK_DATE_FORMAT = "Y-m-d\TH:i:s\Z";
}

/* a set of exceptions we need to handle */
interface EXCEPTIONS {
  const API_USER = "api";
  const RECIPIENT_COUNT_LIMIT = 1000;
  const DEFAULT_TIME_ZONE = "UTC";
  //Common Exception Messages
  const EXCEPTION_INVALID_CREDENTIALS = "Your credentials are incorrect.";
  const EXCEPTION_GENERIC_HTTP_ERROR = "An HTTP Error has occurred! Check your network connection and try again.";
  const EXCEPTION_MISSING_REQUIRED_PARAMETERS = "The parameters passed to the API were invalid. Check your inputs!";
  const EXCEPTION_MISSING_REQUIRED_MIME_PARAMETERS = "The parameters passed to the API were invalid. Check your inputs!";
  const EXCEPTION_MISSING_ENDPOINT = "The endpoint you've tried to access does not exist. Check your URL.";
  const EXCEPTION_OBJECT_DATA = "Could not set up object data for: ";
  const EXCEPTION_OBJECT_ID_NOT_PROVIDED = "No ID was set for this data packet..";
  const TOO_MANY_RECIPIENTS = "You've exceeded the maximum recipient count (1,000) on the to field with autosend disabled.";
  const INVALID_PARAMETER_NON_ARRAY = "The parameter you've passed in position 2 must be an array.";
  const INVALID_PARAMETER_ATTACHMENT = "Attachments must be passed with an \"@\" preceding the file path. Web resources not supported.";
  const INVALID_PARAMETER_PHONE_NUMBER = "The phone number you entered is invalid according to GSM_03.38";
  const INVALID_PARAMETER_INLINE = "Inline images must be passed with an \"@\" preceding the file path. Web resources not supported.";
  const TOO_MANY_PARAMETERS_CAMPAIGNS = "You've exceeded the maximum (3) campaigns for a single message.";
  const TOO_MANY_PARAMETERS_TAGS = "You've exceeded the maximum (3) tags for a single message.";
  const TOO_MANY_PARAMETERS_RECIPIENT = "You've exceeded the maximum count";
  const SCHEMA_HAS_THIS_KEY = "This schema has already used this key.";
  const FIELD_HAS_NOT_BEEN_SET = "This field is not within the schema of: ";
  const KEY_NOT_FOUND_FOR = "Key was not found in fields for: ";
  const WRONG_DATA_PACKET = "Wrong data packet added to: ";
}

interface WARNINGS {
	const WARNING_PARAMETER_TEXT_MESSAGE = "Warning: Text message you entered was too long..";
}

/** defaults used in place of custom parameters **/
interface DEFAULTS {
  const PAGE = 0;
  const SIZE = 10;
  const MAX_SIZE = 1000;
  const SIZE_THRESHOLD = 500;
  const SIZE_MAX = 1000;
  const SIZE_MIN = 0;
  const EXTENSION = ".mp3";
  const ENDPOINT_SEP = "/";
}

/** paths for api endpoints **/
interface PATHS {
  const PATH_TO_MESSAGES = "messages";
  const PATH_TO_CALLS = "calls";
  const PATH_TO_GATHER = "gather";
  const PATH_TO_APPLICATION = "application";
  const PATH_TO_CONFERENCE = "conference";
  const PATH_TO_RECORDINGS = "recordings";
  const PATH_TO_PHONENUMBERS = "phoneNumbers";
  const PATH_TO_NUMBERINFO = "phoneNumbers/numberInfo";
  const PATH_TO_TRANSCRIPTIONS = "recordings/transcriptions";
}

/** BAML definitions **/
interface BAML_VERBS {
  const BAML_SPEAK_SENTENCE = "SpeakSentence";
  const BAML_PLAY_AUDIO = "PlayAudio";
  const BAML_TRANSFER = "Transfer";
  const BAML_GATHER = "Gather";
  const BAML_SEND_MESSAGE = "SendMessage";
  const BAML_REDIRECT = "Redirect";
  const BAML_HANGUP = "Hangup";
  const BAML_RECORD = "Record";
}

interface BAML_XML_METHODS {
   const XML_CHARACTER_DATA_HANDLER = "xml_character_data_handler";
}

interface BAML_XML_HANDLERS {
  const  BAML_PARSE_CHARACTER = "";
}

interface BAML_XML_OPTIONS {
  const  BAML_XML_ENCODING = "UTF-8";
}

interface BAML_SETTINGS {
  const BAML_VERSION = "";
}
