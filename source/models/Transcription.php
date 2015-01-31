<?php
/**
 * @model Transcriptions
 * https://catapult.inetwork.com/docs/api-docs/recording/
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
}


?>
