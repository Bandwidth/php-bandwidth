<?php
/** 
 * @model UserErrors
 * http://ap.bandwidth.com/docs/rest-api/errors/
 *
 * Provides server error logs for your account
 */
namespace Catapult;
final class UserError Extends GenericResource {
    /**
     * Init forms
     * GET
     * UserError('user-error-id')
     * UserError()
     */	
    public function __construct()
    {
      $data = Ensure::Input(func_get_args());

      parent::_init($data, new DependsResource,
        new LoadsResource(array(
                    "primary" => "get", "id" => "id", "init" => "", "silent" => false
        )),
        new SchemaResource(array(
          "fields" => array('id', 'time', 'category', 'code', 'message', 'details', 'version', 'user'),
          "needs" => array("id"))
         )
      );
    }
}
