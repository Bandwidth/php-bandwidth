<?php
namespace Catapult; 
final class RecordingCollection extends CollectionObject { 
  public function getName()
  {
    return "Recording";
  }
}

// some collections
// need to append there parent's id
// as they are unfunctional without
final class TranscriptionCollection extends CollectionObject {
  public function __construct() {
    $args = func_get_args();
    $data = Ensure::Input($args[0]);

    parent::__construct($data, new AppendsResource(array(
      array(
       "term" => "recordingId",
       "link" => TRUE,
       "value" => $args[1]
      )
    )));
  }
  public function getName()
  {
    return "transcription";
  }
}
class MessageCollection extends CollectionObject {
  public function getName()
  {
    return "Message";
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

final class DomainsCollection extends CollectionObject {
  public function getName() 
  {
    return "Domains";
  }
}

final class EndpointsCollection extends CollectionObject {
  public function getName()
  {
    return "Endpoints";
  }
}
