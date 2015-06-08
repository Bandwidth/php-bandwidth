<?php

/**
 * @model Account
 * http://ap.bandwidth.com/docs/rest-api/account/
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
        new SchemaResource(array("fields" => array( "balance", "accountType"), "needs" => array("balance", "accountType")),
        new SubFunctionResource(array("term" => "transactions", "type" => "get"))
      ));
    }


    /**
     * Return the balance
     * in float
     */
    public function getBalance() {
      return (float) $this->get()->balance();
    }
}
