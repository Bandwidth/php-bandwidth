<?php
namespace Catapult;
/* Provided a set of keywords 
 * take them out of the array
 * this is for events when they come
 * with 'callId', or 'conferenceId'
 * keyword we need to take out the keyword
 * afterwards lowercase the key
 *
 * TODO: find alternative if possible
 * this is for events only currently coming
 * in with prefixed ids
 */
final class Cleaner extends BaseUtilities {
	public static $keywords = array(
		"call", "conference", "message"
	);
 /* omits the keyword from
  * the provided dataset
  * where the dataset is a single
  * dimensional array. New keywords without
  * are undercased
  * @param data 
  */
	public function Omit($data)
	{
    foreach ($data as $k => $d) {
      foreach (self::$keywords as $key) {
        if (preg_match("/^$key.*$/", $k, $m)) {
          $nk = strtolower(preg_replace("/$key/", "", $k));
          $data[$nk] = $d;
          unset($data[$k]);
        }
      }
    }

    return $data;
	}
}


?>
