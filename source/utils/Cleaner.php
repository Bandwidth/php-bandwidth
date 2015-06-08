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
  /** organize by run levels **/
	public static $keywords = array(
		"callId"=>0, "conferenceId"=>0,"gatherId" => 1,  "messageId"=>0, "recordingId" => 1, "transcriptionId" => 2, "domainId" => 0, "endpointId" => 1
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
    $runLevel = 0;
    foreach ($data as $k => $d) {
      foreach (array_keys(self::$keywords) as $key) {
        if (preg_match("/^$key$/", $k, $m)) {
          if (self::$keywords[$key] >= $runLevel) {
            $nk = $key;
            $runLevel = self::$keywords[$key];
            // let last runLevel take id
            $data['id'] = $d;
          }
        }
      }
    }

    return $data;
	}
}
