<?php
/**
 * @model Transcriptions
 * http://ap.bandwidth.com/docs/rest-api/recordingsidtranscriptions/
 * under Transcriptions
 *
 * 
 * Provides recording transcriptions
 */
namespace Catapult;

final class Transcription extends GenericResource {

    /**
     * transcriptions need a valid
     * recording. Without fail, this
     * while not checked for authenticity
     * should be in the from r-xxx
     * 
     * Init Forms:
     * 
     * GET
     * Transcription('recording-id', 'transcription-id')
     * Transcription('recording-id')
     * Transcription()
     * 
     * POST
     * Transcription('recording-id', array)
     * Transcription(array)
     */
    public function __construct() {
        $data = Ensure::Input(func_get_args());
        parent::_init($data, new DependsResource(
            array(
                array("term" => "recordings", "plural" => true)
            )),
            new LoadsResource(
                array("primary" => "GET", "id" => "id", 
                "init" => array("recordingId"), 
                "silent" => true, "params")
            ),
            new SchemaResource(
                array("fields" => array('transcriptionUri', 'textSize', 'text', 'status', 
               'textUrl','recordingId', 'state','eventType', 'transcriptionId'), 
               "needs" => array("id"))
            )
        );
    }

    /**
     * Patch for collection
     * based objects we need the
     * path to be loaded seperatly
     * and make sure the recordingId
     * is preserved
     */
    public function load() {
      $data = Ensure::Input(func_get_args());
      $data = $data->get();
      return parent::load($data, new PathResource($this, array(
        "recordings" => $data['recordingId'],
        "transcriptions" => "" 
      )));
    }

    /**
     *
     * treat transcriptions
     * create/0 different 
     * as we need the path
     */
    public function create() {
      $data = Ensure::Input(func_get_args());
      $data = $data->get();
      if (!isset($data['recordingId'])) {
        $data['recordingId'] = "";
      }
  
      return parent::create($data,
        new RemoveResource($this,array("recordingId"),
        new PathResource($this, array(
          "recordings" => $data['recordingId'],
          "transcriptions" => ""
        )
       )
      ));
    }
}
