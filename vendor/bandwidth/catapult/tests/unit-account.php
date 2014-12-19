<?php

/* Unit tests for accounts. These should
 * test the following functions
 * Comparatively to the credential unit tests
 * these run with no try/catching
 *
 *
 *
 * commands tested:
 * get/1
 * get_transactions/1
 */

$cred = new Catapult\Credentials;
$client = new Catapult\Client($cred);


class AccountsTest extends PHPUnit_Framework_TestCase {
	public function testGetTransactions()
	{
		$account = new Catapult\Account;

		$transactions = $account->get_transactions(array(
			"page"=>"0",
			"size"=>"1000"
		));

		$this->assertTrue(sizeof($transactions->get()) > 0);
	}

	public function testGet()
	{
		$account = new Catapult\Account;
		$account->get();
		
		$this->assertTrue((bool) $account->balance);	
	}

}

?>
