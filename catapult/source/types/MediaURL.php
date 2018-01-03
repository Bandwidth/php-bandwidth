<?php
/**
 * @type MediaURL
 * 
 * makes sure the url
 * is an actual
 * media url before dispatch
 *
 * Where the url can be 
 * anchor fixed '@' local
 * or 
 * absolute 
 */

namespace Catapult;

final class MediaURL extends Types {
    public function __construct($media, $name='')
    {
      $this->url = $media;		

      if ($name=='') {
          $this->name = preg_replace("/.*\//", "", $this->url); 
      } else {
        $this->name = $name;
      }
    }

    /**
     * this is for times we need 
     * to check if a media file is 
     * an actual media file
     *
     * Note: this does not download
     * the file only checks if the 
     * return code is valid
     */
    public function isValid() 
    {
      $headers = get_headers($this->url);
      $header = $headers[0]; // the result code
      $type = $headers[sizeof($headers) - 1]; // content type
      $m = array();

      if (preg_match("/\d{3}/", $header, $m)) {

        if (substr($m[0], 0, 1) == "2")
          return true;
      }

      return false;
    }

    public function __toString()
    {
      /** 
       * make a new file on catapult when we receive an anchor  
       * usage follows:
       *
       * @/storage/my.wav => https://api.catapult.inetwork.com/v1/users/u-xxx/media/my
       */
      if (preg_match("/^@/", $this->url, $m)) {
        $media = new Media;
        $media->create(array(
          "file" => preg_replace("/^@/", "", $this->url),
          "mediaName" => $this->name 
         ));
      }

      return $this->url;
    }
}
