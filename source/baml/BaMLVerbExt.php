<?php
/**
 * TODO: BaML should eventually not 
 * use trailing text SpeakSentence
 * in class names. Classes should be
 * like those listed at the bottom of 
 * this file.
 */
namespace Catapult;
class BaMLVerbSpeakSentence extends BaMLVerb {
    public static $params = array(
        "sentence",
        "voice",
        "gender",
        "locale"
    );
}
class BaMLVerbTransfer extends BaMLVerb {
    public static $params = array(
       "transferTo",        
       "transferCallerId"
    );
}
class BaMLVerbPlayAudio extends BaMLVerb {
    public static $params = array(
       "audioUrl",
       "digits"
    );
}
class BaMLVerbRedirect extends BaMLVerb {
    public static $params = array(
       "requestUrl",
       "timeout"
    );
}
class BaMLVerbRecord extends BaMLVerb {
    public static $params = array(
        "requestUrl",
        "requestUrlTimeout",
        "terminatingDigits",
        "maxDuration",
        "transcribe",
        "transcribeCallbackUrl"
    );
}
class BaMLVerbGather extends BaMLVerb {
    public static $params = array(
        "requestUrl",
        "requestUrlTimeout",
        "terminatingDigits",
        "maxDigits",
        "interDigitTimeout",
        "bargeable"
    );
}


class BaMLVerbSendMessage extends BaMLVerb {
    public static $params = array(
        "from",
        "to",
        "requestUrl",
        "requestUrlTimeout",
        "statusCallbackUrl"
    );
}
class BaMLVerbHangup extends BaMLVerb {}
final class BaMLSpeakSentence extends BaMLVerbSpeakSentence {}
final class BaMLTransfer extends BaMLVerbTransfer {}
final class BaMLPlayAudio extends BaMLVerbPlayAudio {}
final class BaMLRedirect extends BaMLVerbRedirect {}
final class BaMLGather extends BaMLVerbGather {}
final class BaMLSendMessage extends BaMLVerbSendMessage {}
final class BaMLHangup extends BaMLVerbHangup {}
