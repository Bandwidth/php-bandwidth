<?php

namespace Catapult;
/**
 * All these are used directly by the models
 * they are passed in the constructors and validate,
 * and make the models. Order of execution follows
 * DependsResource, LoadsResource, SchemaResource, SubFunctionResource (optional)
 *
 * TODO make metaresurce initializa
 * more than only the SubFunctions
 */

abstract class MetaResource extends BaseResource {
    public function __construct($depends=null) {
        $this->terms = array();
        $checks = array("plural", "mandatory");
        if (!is_array($depends))
            return;

        foreach ($depends as $k => $d) {
            $this->terms[$k] = new SubFunctionObject($d);
        }
    }
}

/**
 * General purpose carrying of multiple
 * parameter objects for Schema, Depends objects
 * 
 * needs subclass to inherit with terms
 */
abstract class Multi {
    public function __construct($args) {
        if (is_array($args)) {
           
          foreach ($this->terms as $t) {
             if (!in_array($t, array_keys($args))) {
                $this->$t = false;
                continue;
             }
             
              $this->$t = $args[$t];
          } 
       }
    }
}


final class SubfunctionObject extends Multi {
    public $terms = array( "term", "type", "singular" ); 
}

/**
 * object for dependancies
 * in models. Used with DependsResource
 *
 */
final class DependsObject extends Multi {
    public $terms = array(
        "term",
        "plural",
        "mandatoryId" 
    ); 
}

/**
 * subfunction resource will register
 * functions that are derived from another object
 * i.e
 * in calls:
 * getTranscriptions
 * this will be however defined in calls. 
 * 
 */
class SubFunctionResource extends MetaResource {
    public $terms = array(
        "term",
        "type",
        "id",
        "plural"
    );
}

/**
 * remove properties when
 * done with computation needed
 * in certain areas
 */
class RemoveResource {
  public function __construct(&$object, $terms) {
    foreach ($terms as $v) {
      unset($object->$v);
    }
  }
}


/**
 * Depends resource, these
 * distinguish whats needed for a model
 * primarly used for building paths
 * and finding out whether to create or
 * get a model on initialization
 * each model should have this embedded
 */

final class DependsResource extends BaseResource {
    public static $keywords = array(
        "plural",
        "term",
        "mandatoryId"
    );
    /**
     * fields known to depends,
     * 'plural', 'mandatoryId'
     *
     * in some cases we look
     * for plural terms and 
     * ids 'not' being plural
     * you can set this in the assoc array
     *
     */
    public function __construct($depends=null) {
        $this->terms = array();

        if (!is_array($depends))
            return;

        foreach ($depends as $k => $d) {
            if (!in_array($k, $this::$keywords))
                throw new \CatapultApiException("Fields were built improperly for " . __CLASS__);

            $this->terms[$k] = new DependsObject($d);
        }
    }
}


/**
 * Client resource has one function it attaches
 * the main client to this model, by reference
 * outcome should be all objects pointing to the
 * same client
 *
 * when no client is available throw a warning
 * this helps the user figure where the code
 * is faulty
 */
class ClientResource extends BaseResource {
    public static function attach(&$object) {
        $client = Client::Get();
        $object->client = &$client;
        if ($object->client == null) {
            throw new \CatapultApiException("You have not initialized the client yet. Please use: Catapult\Client(params..)");
        }
    }
}

?>
