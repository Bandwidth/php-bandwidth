<?php
namespace Catapult;
/**
 * Quick prototypes for RESTClient
 * allow get/create/update/patch
 * to be mocked and dynamically defined
 *  
 *
 * Input should always guarantee atleast two
 * parameters. term and object
 */
class PrototypeUtility extends BaseUtilities {

    public function get_idx($args, $idx) {
      return $args[$idx];
    }
    /**
     * Get this from a static
     * context. Will be used
     * for the other methods.
     * last parameter should be the object
     *
     * @param args: func_get_args
     */
    public static function get_this($args) 
    {
      return self::get_idx($args, sizeof($args) - 1);
    }

    /**
     * get the term
     * associated with
     * this subfunction
     * should be a valid term 
     * in Catapult models
     *
     * @param args: func_get_args
     */
    public function get_term($args)
    {
      return self::get_idx($args, sizeof($args) - 2);
    }

    /**
     * IDs for subfunctions
     * can be found in the first
     * parameter
     *  
     * @param args: func_get_args
     */
    public function get_id($args)
    {
      return self::get_idx($args, 0);
    }

    /**
     * IDs for subfunctions
     * can be found in the first
     * parameter
     *
     * @param args: func_get_args
     */
    public function get_is_plural($args)
    {
      return self::get_idx($args, 1);
    }


    /**
     * Mock get functions
     * read whether we need to 
     * use an id or not
     * then run accordingly
     * last parameter will always
     * be 'this' object
     */
    public static function get()
    {
       $args = func_get_args();
       
       $that = self::get_this($args);
       $term = self::get_term($args);
       $id = self::get_id($args);
       $plural = self::get_is_plural($args);

       if ($plural) {
         $url = URIResource::Make($that->path, array($that->id, GenericResource::getPath($term)));
         $dp = new DataPacketCollection($that->client->get($url));
         $cl = GenericResource::getObjClass($term);
         $pobjcl = "Catapult\\" . $cl . "Collection";
         $pobj = new $pobjcl($dp);
               
         return $pobj;
       } else {
         $url = URIResource::Make($that->path, array($that->id, GenericResource::getPath($term)));
         $dp = new DataPacket($that->client->get($url));
         $cl = GenericResource::getObjClass($term);
         $pobjcl = "Catapult\\" . $cl;
         $pobj = new $pobjcl;

         return Constructor::Make($pobj, $dp->get());
       }


       return Constructor::Make($pobj, $dp->get());
    }

    /**
     * prototypal add. This needs
     * term to be set as a function 
     * in its caller it will be used
     * to initialize the resulting
     * object
     *
     * Conference->addMember 
     * should return
     * ConferenceMember
     *
     * @params: mix of function arguments as well as object prototypes, terms
     */
    public static function add(/** polymorphic **/)
    {
       $args = func_get_args();
       $that = self::get_this($args);
       $term = self::get_term($args);

       $url = URIResource::Make($that->path, array($that->id, $term));

       $id = Locator::Find($that->client->post($url, $args->get()));
        
       return $this->$term($id);
    }

    /**
     * Prototypal update.
     * must have sub id to reference
     * allows mocking
     * of functions like: 
     *
     * Conference->updateMember(array(memberId=1))
     *
     * termId should always be
     * term + Id
     * make sure it is singular.
     *
     */
    public static function update(/** polymorphic **/)
    {
      $args = func_get_args();
      $that = self::get_this($args);
      $term = self::get_term($args);
      $data = Ensure::Input($args);
      $termId = TitleUtility::ToSingular($term) . "Id";

      $ret = $this->client->post($url, array($term, $data->get($termId)));

      return $this->$term($ret['id']);
    }

}
