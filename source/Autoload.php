<?php
/**
 * Catapult's built in Autoload function
 * when using composer this is not needed,
 *
 */
function includeDir($path) {
    $dir      = new RecursiveDirectoryIterator($path);
    $iterator = new RecursiveIteratorIterator($dir);
    $loaded = array();
    $ignore = array("..", ".");
    $file_len = sizeof(scandir($path));
    while (sizeof($loaded) != $file_len) {

      foreach ($iterator as $file) {
        $fname = $file->getFilename();
        $score = 0;

        if (!in_array($fname, $loaded)) {
          $files = scandir($path);
          foreach ($files as $fname1) {
          
            if ($fname1 !== $fname 
            && !in_array($fname1, $loaded)
            && $fname1 >= $fname) 
                  $score ++; 
                
            }
          if ($score ==($file_len - 1) - (sizeof($loaded))) {
            if (!in_array($fname,$ignore)) {
              require_once($file);
              $loaded[]=  $fname;
             } else {
              $loaded[] = $fname;
             }
          }
        }
     }
  }

}
