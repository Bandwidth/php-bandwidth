<?php
/**
 * @model Transaction
 * https://catapult.inetwork.com/docs/api-docs/account/#GET-/v1/users/{userId}/account/transactions
 *
 * For account transactions
 * 
 */
namespace Catapult;
final class Transaction extends GenericResource {
    /**
     * Transactions are a subclass of account 
     * usually called through account, rather than
     * here.
     *
     * Init forms
     * GET
     * Transaction('transaction-id') 
     */
    public function __construct($data=null)
    {
       $data = Ensure::Input($data);
       parent::_input($data, new DependsResource(
        array(
          array( "term" => "accounts", "plural" => false))
        ),
        new LoaderResource(
          array("primary" => "GET", "id" => "id", "silent" => false)
        ),
        new SchemaResource(
          array("fields" => array("id"))
        )
      );
    }
}


?>
