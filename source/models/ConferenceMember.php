<?php

/**
 * @model ConferenceMember
 * http://ap.bandwidth.com/docs/rest-api/conferences/#resource427
 * 
 * Functions to create, update and delete
 * conference members
 *
 */

namespace Catapult;

/* Represent a member in a conference
 * methods to get audio files, get member
 * and update
 */
final class ConferenceMember extends AudioMixin {
   /**
    * CTor for conference memebrs 
    *
    * Init forms:
    * GET
    * ConferenceMember('member-id')
    * ConferenceMember()
    * 
    * POST
    * ConferenceMember('conference-id', array)
    * ConferenceMember(array)
    */
    public function __construct()
    {
      $data = Ensure::Input(func_get_args());
        parent::_init($data, new DependsResource(array(
            array("term" => "conference", "plural" => true, "silent" => false))
           ),
           new LoadsResource(array("primary" => "GET", "init" => array("conferenceId"), "id" => "id")),
           new SchemaResource(array("fields" => array(
                'id', 'state', 'added_time', 'hold', 'mute', 'join_tone', 'leaving_tone'
            ), "needs" => array("id", "state", "from"))
          )
       );
    }

    /**
     * Get audio url
     * for conference member
     */
    public function getAudioUrl()
    {
      return URIResource::Make($this->path, array($this->conference, "members", "audio"));
    }
}
