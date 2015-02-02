<?php

/**
 * @model Account
 * https://catapult.inetwork.com/docs/api-docs/account/
 *
 * provides access to account information
 * and transactions
 *
 * provides: 
 * get/0
 *
 */
namespace Catapult;

final class Account extends GenericResource {

    /**
     *
     * Init forms:
     *
     * GET 
     * Account()
     *
     */
    public function __construct($data=null) {
      $data = Ensure::Input($data);

      parent::_init($data, new DependsResource,
        new LoadsResource(array("primary" => "GET", "id" => "id", "init" => "", "silent" => false)),
        new SchemaResource(array("fields" => array( "balance", "account_type"), "needs" => array("balance", "account_type")),
        new SubFunctionResource(array("term" => "transactions", "type" => "get"))
      ));
    }
}


?>
