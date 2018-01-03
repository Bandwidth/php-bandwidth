<?php
namespace Catapult;
/**
 * functions to pluralize and
 * singularize catapult meta, model and terms.
 * all these should use the actual terms before
 * 
 * TODO: move logic from GenericResource here
 */
final class TitleUtility extends BaseUtilities {
    /**
     * Always check if we already
     * have this plural. Best option
     * is to lookup the plural form
     * fallback to 's' appending
     *   
     * @param term: Catapult term
     */
    public static function ToPlural($term) {
      if (!preg_match("/s$/", $term, $m)) {
        return "{$term}s";
      }
       
      return $term; 
    }

    /**
     * same as toPlural for
     * singular
     * @param term: Catapult term
     */
    public static function ToSingular($term) {
      if (preg_match("/s$/", $term, $m)) {
        return preg_replace("/s$/", "", $term);
      }

      return $term;
    }

    /**
     * standard title
     * case. this is for
     * singular words only
     *
     * @param term: Catapult model term
     */
    public static function ToTitlecase($term) {
      return strtoupper(substr($term, 0, 1)) . strtolower(substr($term, 1, strlen($term)));
    }
}
