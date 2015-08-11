<?php
/**
 * @model Message
 * http://ap.bandwidth.com/docs/rest-api/messages/
 *
 *
 * This provides SMS and MMS interface for Catapult
 * 
 */
namespace Catapult;

class Message extends GenericResource {
    /**
     * CTor to message object
     * inherit base provide all implementation here.
     * 
     * Init form:
     * GET
     * Message('message-id')
     * Message()
     *
     * POST
     * Message(array)
     *
     */
    public function __construct(/* polymorphic */)
    { 
      $data = Ensure::Input(func_get_args());
      parent::_init($data, new DependsResource,
        new LoadsResource(
          array("primary" => "GET", "id" => "id", "init" => "", "silent" => false)
        ),
        new SchemaResource(
          array("fields" => array(
          'id', 'direction', 'callbackUrl', 'callbackTimeout',
          'fallbackUrl', 'from', 'to', 'state', 'time', 'text',
          'errorMessage', 'tag', 'media', 'receiptRequested'
           ), "needs" => array("id", "from", "to", "state"))
         ),
         new SubFunctionResource
      );
    }
    
    /* stub for property access by resolver */
    public function create()
    {
      $args = Ensure::Input(func_get_args());
      return $this->send($args->get());
    }

    /**
     * Send message with additional parameters
     * important rewrite in place of
     * more polymorphic style. 
     * i.e send(from, to, message, calback)
     *
     * @param args:  list of valid parameters
     */	
    public function send($args)
    {
      $data = Ensure::Input($args);
      $url = URIResource::Make($this->path);

      if ($data->has("media")) {
         $data->add("media", (string) new MediaURL($data->val("media", $this)));   
      }
          
      $message_id = Locator::Find($this->client->post($url, $data->get()));
      $data->add("id", $message_id);

      return Constructor::Make($this, $data->get(), array("messageId" => "id"));
    }
}
