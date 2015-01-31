<?php
/**
 * Catapult's built in Autoload function
 * when using composer this is not needed,
 *
 */
function includeDir($path) {
    $dir      = new RecursiveDirectoryIterator($path);
    $iterator = new RecursiveIteratorIterator($dir);
    
    $alpha = array('a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z');
    $curr = 'a';
    $loaded = 0;
    while ($loaded != sizeof($alpha)) {
    foreach ($iterator as $file) {
        $fname = $file->getFilename();
        $t = strtolower(substr($fname, 0, 1));
        if ($t == $curr) {
           require_once($file->getPathname());
        }

      }
      $curr ++;
      $loaded ++;
   }
}
?>
