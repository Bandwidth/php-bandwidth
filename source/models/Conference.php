<?php

/**
 * @model Conference
 * http://ap.bandwidth.com/docs/rest-api/conferences/
 *  
 * Creates conferences through
 * Catapult.
 *
 */
namespace Catapult;

final class Conference extends AudioMixin {

    /**
     *
     * Init forms:
     * 
     * GET 
     * Conference('conference-id')
     * Conference()
     * 
     * POST
     * Conference(array)
     */
    public function __construct()
    {
      $data = Ensure::Input(func_get_args());

      parent::_init($data, new DependsResource,
        new LoadsResource(
          array("primary" => "GET", "id" => "id", "silent" => false)
        ),
        new SchemaResource(
          array("fields" => array("id", "state", "from", "created_time", "completed_time", "fallback_url"), 
              "needs" => array(
              "id", "state", "from"
              )
          )),
        new SubFunctionResource(array(
          array("type" => "get", "term" => "members"),
          array("type" => "add", "term" => "members"),
          array("type" => "update", "term" => "member")
         )
        )
       );        
    }

   /**
    * Add a member inside
    * a conference
    *
    * @param id: Catapult id
    * @param params -> List of member parameters
    *       joinTone
    *       leavingTone
    */
    public function addMember($params) 
    {
      $args = Ensure::Input($params);
      $url = URIResource::Make($this->path, array($this->id, "members"));
      $memberid = Locator::Find($this->client->post($url, $args->get()));
    
      return $this->member($memberid);
    }

   /**
    * point to a
    * member and update
    *
    * @param params: set of arguments with
    * with memberId
    */ 
    public function updateMember($params)
    {
      $args = Ensure::Input($params);
      $url = URIResource::Make($this->path, array($this->id, "members", $args->val("memberId")));
      $member = $this->client->post($url, $args->get());

      return $this->member($member['id']);
    }

    /**
     * Return a partial for
     * the member selected
     *
     */
    public function member()
    {
      return new ConferenceMember($this->id);
    }
}
