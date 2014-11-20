<?php
namespace Catapult;


/** these will be used across the unit tests, they have no meaning in the api **/
define('__DEFAULT_SENDER__', '+14244443192');
define('__DEFAULT_RECEIVER__', '+14244443192');
define('__DEFAULT_URL__', 'http://bandwidth.com/');
define('__APPLICATION_UNIT_TEST__', 'UNIT TEST APPLICATION');

/** runtime settings for debug / production release **/
interface API_MODE {
	const API_IN_DEBUG_MODE = 1;
}

/* do NOT change these. These will be exchanged at runtime */
interface API {
	const API_ENDPOINT = "https://api.catapult.inetwork.com";
	const APPLICATION_JSON = "application/json"; 
	const API_METHOD_POST = "POST";
	const API_METHOD_DEL = "DELETE";
	const API_METHOD_PUT = "PUT";
	const API_METHOD_GET = "GET";
	const API_BANDWIDTH_USER_ID = "BANDWIDTH_USER_ID";
	const API_BANDWIDTH_TOKEN = "BANDWIDTH_API_TOKEN";
	const API_BANDWIDTH_SECRET = "BANDWIDTH_API_SECRET";
	const API_BANDWIDTH_VALID_NUMBERS = "BANDWIDTH_API_VALID_NUMBERS";
	const SDK_VERSION = "0.1";
	const SDK_USER_AGENT = "catapult-sdk-php";
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
	const PAGE_SIZE = 0;
	const SIZE = 25;
	const SIZE_MAX = 1000;
	const SIZE_MIN = 0;
	const EXTENSION = ".mp3";
	const ENDPOINT_SEP = "/";
}

/** paths for api endpoints **/
interface PATHS {
	const PATH_TO_MESSAGES = "messages";
	const PATH_TO_CALLS = "calls";
	const PATH_TO_APPLICATION = "";
	const PATH_TO_CONFERENCE = "";
	const PATH_TO_RECORDINGS = "";
}
?>
