<?php
/**
 * @type FileHandler
 * 
 * Minimal filehandler
 * for media types.  
 */ 
namespace Catapult;
final class FileHandler extends Types {
    public static function save($as=null, $contents)
    {
      if (!self::try_directory($as)) {
        return -1;
      }


      return file_put_contents(realpath($as) . $as, $contents);
    }	

    public static function read($filename)
    {
      if (!(is_file(realpath($filename)))) {
        throw new \CatapultApiException("File does not exist");
      }

      return file_get_contents(realpath($filename));
    }

    /**
     * make a directory
     * if needed 
       * @param fully qualified path
     */
    public static function try_directory($file)
    {
      $matches = array();
      preg_match("/(.*\/).*$/", $file, $matches);
      
      try {
        if (sizeof($matches) >= 1) {
          $folder = $matches[1];

          if (!(is_dir($folder))) {
            mkdir($folder);
            return 1;
          }
        }
      } catch (Exception $e) {
        /** do not handle this exception it is low level and will be warned by the caller **/

        return -1;
      }

      return 1;
    }

    public function __toString()
    {
      return (string) $this->as;
    }
}
