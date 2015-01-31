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

        if ($name=='')
            $this->name = preg_replace("/.*\//", "", $this->url); 
        else
            $this->name = $name;
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

?>
