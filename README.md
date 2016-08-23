# PHP-bandwidth
[![Build Status](https://travis-ci.org/bandwidthcom/php-bandwidth.svg?branch=v2-preview)](https://travis-ci.org/bandwidthcom/php-bandwidth) [![Latest Stable Version](https://poser.pugx.org/bandwidth/catapult/version)](https://packagist.org/packages/bandwidth/catapult) [![Latest Unstable Version](https://poser.pugx.org/bandwidth/catapult/v/unstable)](//packagist.org/packages/bandwidth/catapult) [![License](https://poser.pugx.org/bandwidth/catapult/license)](https://packagist.org/packages/bandwidth/catapult)


SDK for [Bandwidth's App Platform](http://ap.bandwidth.com/?utm_medium=social&utm_source=github&utm_campaign=dtolb&utm_content=)


## Installing the SDK
During development, we only support running from source:

to include "Bandwidth.php" from /source/

Example:

```require "source/Bandwidth.php"```

## Supported Versions
`php-bandwidth` should work on all versions of php newer than `5.5`. However, due to PHP's release cycle, we can only provide _support_ on [Currently Supported Versions of PHP](https://secure.php.net/supported-versions.php).


| Version | Support Level |
|---------|---------------|
| <5.5.* | Unsupported |
| 5.6.* | Supported |
| 7.0.* | Supported **Recommended**|
| >=7.1 | Unsupported |

## Client initialization

All interaction with the API is done through a `client` Object. The client constructor takes an Object containing configuration options. The following options are supported:

| Field name  | Description           | Default value                       | Required |
|-------------|-----------------------|-------------------------------------|----------|
| `userId`    | Your Bandwidth user ID | `undefined`                         | Yes      |
| `apiToken`  | Your API token        | `undefined`                         | Yes      |
| `apiSecret` | Your API secret       | `undefined`                         | Yes      |
| `baseUrl`   | The Bandwidth API URL  | `https://api.catapult.inetwork.com` | No       |

To initialize the client object, provide your API credentials which can be found on your account page in [the portal](https://catapult.inetwork.com/pages/catapult.jsf).

## Rest API Coverage
------------
* [Account](http://ap.bandwidth.com/docs/rest-api/account/)
    * [ ] Information
    * [ ] Transactions
* [Applications](http://ap.bandwidth.com/docs/rest-api/applications/)
    * [ ] List
    * [ ] Create
    * [ ] Get info
    * [ ] Update
    * [ ] Delete
* [Available Numbers](http://ap.bandwidth.com/docs/rest-api/available-numbers/)
    * [ ] Search Local
    * [ ] Buy Local
    * [ ] Search Tollfree
    * [ ] Buy Tollfree
* [Bridges](http://ap.bandwidth.com/docs/rest-api/bridges/)
    * [ ] List
    * [ ] Create
    * [ ] Get info
    * [ ] Update Calls
    * [ ] Play Audio
        * [ ] Speak Sentence
        * [ ] Play Audio File
    * [ ] Get Calls
* [Calls](http://ap.bandwidth.com/docs/rest-api/calls/)
    * [ ] List all calls
    * [ ] Create
    * [ ] Get info
    * [ ] Update Status
        * [ ] Transfer
        * [ ] Answer
        * [ ] Hangup
        * [ ] Reject
    * [ ] Play Audio
        * [ ] Speak Sentence
        * [ ] Play Audio File
    * [ ] Send DTMF
    * [ ] Events
        * [ ] List
        * [ ] Get individual info
    * [ ] List Recordings
    * [ ] List Transciptions
    * [ ] Gather
        * [ ] Create Gather
        * [ ] Get Gather info
        * [ ] Update Gather
* [Conferences](http://ap.bandwidth.com/docs/rest-api/conferences/)
    * [ ] Create conference
    * [ ] Get info for single conference
    * [ ] Play Audio
        * [ ] Speak Sentence
        * [ ] Play Audio File
    * [ ] Members
        * [ ] Add member
        * [ ] List members
        * [ ] Update members
            * [ ] Mute
            * [ ] Remove
            * [ ] Hold
        * [ ] Play Audio to single member
            * [ ] Speak Sentence
            * [ ] Play Audio File
* [Domains](http://ap.bandwidth.com/docs/rest-api/domains/)
    * [ ] List all domains
    * [ ] create domain
    * [ ] Delete domain
* [Endpoints](http://ap.bandwidth.com/docs/rest-api/endpoints/)
    * [ ] List all endpoints
    * [ ] Create Endpoint
    * [ ] Get Single Endpoint
    * [ ] Update Single Endpoint
    * [ ] Delete Single Endpoint
    * [ ] Create auth token
* [Errors](http://ap.bandwidth.com/docs/rest-api/errors/)
    * [ ] Get all errors
    * [ ] Get info on Single Error
* [Intelligence Services](http://ap.bandwidth.com/docs/rest-api/intelligenceservices/)
    * [ ] Number Intelligence
* [Media](http://ap.bandwidth.com/docs/rest-api/media/)
    * [ ] List all media
    * [ ] Upload media
    * [ ] Download single media file
    * [ ] Delete single media
* [Messages](http://ap.bandwidth.com/docs/rest-api/messages/)
    * [ ] List all messages
    * [ ] Send Message
    * [ ] Get single message
    * [ ] [Batch Messages](http://ap.bandwidth.com/docs/rest-api/messages/#resourcePOSTv1usersuserIdmessages) (single request, multiple messages)
* [Number Info](http://ap.bandwidth.com/docs/rest-api/numberinfo/)
    * [ ] Get number info
* [Phone Numbers](http://ap.bandwidth.com/docs/rest-api/phonenumbers/)
    * [ ] List all phonenumbers
    * [ ] Get single phonenumber
    * [ ] Order singe number
    * [ ] Update single number
    * [ ] Delete number
* [Recordings](http://ap.bandwidth.com/docs/rest-api/recordings/)
    * [ ] List all recordings
    * [ ] Get single recording info
* [Transciptions](http://ap.bandwidth.com/docs/rest-api/recordingsidtranscriptions/)
    * [ ] Create
    * [ ] Get info for single transcrption
    * [ ] Get all transcrptions for a recording
* [BXML](http://ap.bandwidth.com/docs/xml/)
    * [ ] Call
    * [ ] Conference
    * [ ] Gather
    * [ ] Hangup
    * [ ] Media
    * [ ] Pause
    * [ ] PlayAudio
    * [ ] Record
    * [ ] Reject
    * [ ] SendMessage
    * [ ] SpeakSentence
    * [ ] Transfer

### Tooling and Patterns
Inspired by and borrowed from:

* [Dropbox SDK](https://github.com/dropbox/dropbox-sdk-php)
* [Stripe SDK](https://github.com/stripe/stripe-php)
