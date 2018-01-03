<?php
/**
 * @model Media
 * http://ap.bandwidth.com/docs/rest-api/media/
 *
 *
 * Storage for Catapult API. 
 *
 */
namespace Catapult;

final class Media extends GenericResource {
    /**
    * Construct a media object
    * where data must be a blob
    * in binary. Store in memory until
    * store/1 is called
    * if data is passed use this as object
    * otherwise initialize from passed id.
    * In rare cases when media is called only to list or create
    * disregard both data and id
    *
    *
    * Init Forms:
    * GET
    * Media('media-id')
    *
    * PUT
    * Media(array)
    *
    */
    public function __construct() {
      $data = Ensure::Input(func_get_args());
      parent::_init($data, new DependsResource,
        new LoadsResource(
          array("primary" => "GET", "id" => "content", "init" => "", "silent" => true)
        ),
        new SchemaResource(
          array("fields" => array("contentLength", "mediaName", "content"), "needs" => array("mediaName"))
        )
      );
    }

    /**
     * @param args: same as upload
     */
    public function create()
    { 
      $input  = Ensure::Input(func_get_args());
      return $this->upload($input->get());
    }

    /**
     * get/1 should find the
     * media contents and set in the contents
     *
     * see getMediaContents for the file fetching
     */
    public function get($mediaName=null) {
      $contents = $this->getMediaContents($mediaName);
      $this->setData($content);
    }
    /**
     * get the media contents
     *
     * TODO get/0 needs reimplementation
     * see: http://ap.bandwidth.com/docs/rest-api/media/#resource441
     *
     * @param mediaName a media you uploaded
     * @return the media content either wav/mp3
     */
    public function getMediaContents($mediaName) {
      $content = $this->client->get($this->path . "/" . $mediaName, array());
       
      $this->setData($content); 

      // return the content
      return $content;
    }
    /**
     * Upload new media.  
     * 
     * 
     * In remaking we need the url. As this is 
     * a PUT request no 'location' header would
     * be present [spec]
     *
     * we will need both the mediaName and url
     *
     * @param args
     * must contain fileName and file(path to file)
     */
    public function upload($args)
    {
      $args = Ensure::Input($args);
      $data = $args->get();

      $url = URIResource::Make($this->path, array($data["mediaName"]));

      if (isset($data['file'])) {
        $file = FileHandler::Read($data['file']);
      } else {
        $file = $this->data;
      }
      
      $this->client->put($url, $file);

      return Constructor::Make($this, array_merge(array("url" => $this->client->join($url)), $data));
    }

    /**
     * Set the data. 
     * Input needs raw string
     * usually called by recording
     *
     * @param data: binary contents
     */
    public function setData($data) 
    {
      $this->data = $data;
    }

    /**
     * Store media as file 
     * on the fs
     *
     * By default create a directory if not
     * currently available
     *
     * @param filename: full file name
     * @param extention: extention to save in 
     */
    public function store($filename, $filext=DEFAULTS::EXTENSION)
    {	
      return FileHandler::save($filename, $this->data);
    }
}
