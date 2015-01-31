<?php
namespace Catapult; 
final class RecordingCollection extends CollectionObject { 
    public function getName()
    {
        return "Recording";
    }
}
final class TranscriptionCollection extends CollectionObject {
    public function getName()
    {
        return "transcription";
    }
}
class MessageCollection extends CollectionObject {
    public function getName()
    {
        return "message";
    }
}
final class BridgeCollection extends AudioMixin {
       public function getName()
       {
             return "Bridge";
       }
}
final class EventCollection extends CollectionObject  {
    public function getName()
    {
        return "Event";
    }
}
final class PhoneNumbersCollection extends CollectionObject {
    public function getName()
    {
        return "PhoneNumbers";
    }
}
final class TransactionCollection extends CollectionObject {
    public function getName()
    {
        return "Transaction";
    }
}
final class CallCollection extends CollectionObject { 
    public function getName()
    {
        return "Call";
    }
}
final class UserErrorCollection extends CollectionObject {
    public function getName()
    {
        return "UserError";
    }
}
final class MediaCollection extends CollectionObject {
    public function getName()
    {
        return "Media";
    }
}
final class GatherCollection extends CollectionObject {
    public function getName()
    {
        return "Gather";
    }
}
final class ApplicationCollection extends CollectionObject {
    public function getName()
    {
        return "Application";
    }
}
final class CallEventsCollection extends CollectionObject {
    public function getName()
    {
        return "CallEvents";
    }
}

?>
