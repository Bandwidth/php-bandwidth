<?php
namespace Catapult;
/**
 * Built in logger for Catapult SDK
 * will be used on exceptions, folders generated
 * relative to working directory. 
 * 
 * NOTE if you are using ./logs for something else
 * you can reset the directory using
 * 
 * Catapult\Log::directory('new_directory');
 *
 * To set off/on the logger
 *
 * Catapult\Log::on(TRUE);
 *
 * example output:
 * /logs/catapult_log_12-12-2014-2:05.log
 */

final class Log {

    public static $on = TRUE;
    public static $log_file;

    /**
     * General purpose options
     * for logger
     */
    public static $logger_opts = array(
      "path" => __DEFAULT_LOG_USER_PATH__,
      "mode" => "a" // usually append unless needed for read only
    );


    /**
     * close the open logger
     *
     */
    public static function close()
    {
      return fclose(self::$log_file);	
    }

    /**
     * can we find a member
     * for this log.
     * match by Y-M-D 
     * 
     */
    public static function find()
    {
      $files = scandir(self::$logger_opts['path']);

      $current = new \DateTime();
      $current->setTimestamp(time());
      $term = __DEFAULT_LOG_PREFIX__ . $current->format("Y-M-D");

      foreach ($files as $f) {
        $matches = array();
        preg_match("/^$term.*$/", $f, $matches);

        if (sizeof($matches) > 0) {
          return $f;
        }
      }


      return FALSE;
    }

   /**
    * open the current
    * log. Set by day.
    * If we cannot open a folder,
    * file set catapult logging off
    *
    * by default this will output in the current
    * directory. If there is a logs directory in the
    * folder, it will use that
    */
    public static function open()
    {
      $date_of_log = new \DateTime();

      $date_of_log->setTimestamp(time());

      try {

        if (!(is_dir(self::$logger_opts['path']))) {
          mkdir(self::$logger_opts['path']);
        }

        if (($pfile = self::find())) {
          $file = realpath(self::$logger_opts['path']) . DIRECTORY_SEPARATOR  . $pfile;
        } else {
          $file = realpath(self::$logger_opts['path']) . DIRECTORY_SEPARATOR  . __DEFAULT_LOG_PREFIX__ . $date_of_log->format("Y-M-D-h-i-s") . ".log";
        }

        self::$log_file = fopen($file, self::$logger_opts['mode']);

      } catch (\Exception $e) {

        return self::on(FALSE);

      }
    }

    /**
     * Write to the log
     * accept a date, object and format 
       *
       * Format: {file} - {app_id} [{time}] "{object}" {message} 
     * @param time: unix timestamp
     * @param object: Catapult Model Object
     * @param file:
     */
    public static function write($time='', $object='CALL', $message='')
    {
      if (!(self::$on)) {
        return FALSE;
      }

      if (self::$log_file || !isset(self::$log_file)) {
        self::open();
      }


      /** who was the logger called from if we are running on a server **/
      /** this is easy with $_SERVER['PHP_SELF'], if running independatly, **/
      /** we need introspection **/
      if (isset($_SERVER['PHP_SELF'])) {
        $file = realpath($_SERVER['PHP_SELF']);
      } else {
        /** to implement **/
        /** show directory until optimal introspection is found **/
        $file = realpath(__DIR__);
      }
      

      if (is_int($time)) {
        $date = new \DateTime;
        $date->setTimestamp($time);
        $time = $date->format("M/m/y:H:i:s");
      }


      $cli = Client::get();

      $fulltext = "$file ($object) - APPLICATION:" . $cli->application_id . " [$time] - $message" . "\n";

      fwrite(self::$log_file, $fulltext); 
    }

    public static function isOn()
    {
      return self::$on;
    }

    /**
     * turn on/off logging
     * @param on: TRUE|FALSE
     */
    public static function on($on=TRUE)
    {
      return self::$on = $on;
    }
  }
