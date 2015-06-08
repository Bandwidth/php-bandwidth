<?php

/**
 * @object MessageMulti
 * 
 * MessageMulti provides batch based
 * message processing while always keeping
 * track of which messages were sent, errors
 *
 */
namespace Catapult;

class MessageMulti extends GenericResource {
    private $path = "messages";
      public static $args = array(
          "from",
          "to",
          "text",
          "callbackUrl",
          "timeout",
          "media"
      );

    public function __construct($messages=array())
    {
      $this->client = Client::Get();
      $this->messages = $messages;
      $this->complete = FALSE;
    }

    /**
     * Important this function does not
     * provide 'CollectionObject' thus cannot
     * be enumerated. TODO: fix
     */
    public function listMessages($arr)
    {
      $this->messages = array();
      $this->errors = array();

          return $this->messages;
    }

    /**
     * push a message to
     * the queue
     * @all params -> string to objects according to api.
     */
    public function pushMessage(/** polymorphic **/)
    {
      $args = func_get_args();
      $args = $args[0];
      $out = array();
      $cnt = 0;

      foreach ($args as $arg) {
          $out[self::$args[$cnt]] = $arg; 
          $cnt ++;
      }

      $message = Ensure::Input($out);

      $this->messages[] = $message->get();
    }

    public function execute()
    {
      if ($this->complete)
        Throw new \CatapultApiException("You\'ve already done this");

      $msgs = $this->post_messages();
      $smsgs = array();

      foreach ($msgs as $msg) {
        $data = array_pop($this->messages);

        if (isset($msg->location)
           || isset($msg->location)) {
          $data['id'] = $msg->location;
          $data['state'] = MESSAGE_STATES::sent;
          $smsgs[] = $msg;
        } elseif (isset($msg->error)
           || isset($msg->error)) {
          $data['error_message'] = $msg->error->message;
          $data['state'] = MESSAGE_STATES::error;
          $smsgs[] = $msg;
        }
      }

      return $smsgs;
    }

    /**
     * multiform send
     * messages output should
     * satisfy array
     */
    protected function postMessages()
    {
      $url = URIResource::Make($this->path);

      $messages = $this->client->post($url, $this->messages);
    
      return $messages;
    }
}
