<?php
/**
 * @model Transaction
 * http://ap.bandwidth.com/docs/rest-api/account/#resource94
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
          array( "term" => "account", "plural" => false))
        ),
        new LoadsResource(
          array("primary" => "GET", "id" => "id", "init" => "id", "silent" => false)
        ),
        new SchemaResource(
          array("fields" => array("id"), "needs" => array("id"))
        )
      );
    }
}


?>
