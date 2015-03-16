<?php

/**
 * Unit tests for helper functions/utils 
 * 
 * classes tested
 * EnsureResource
 * URIResource
 * ResolverResource
 * utils
 */

$cred = new Catapult\Credentials;
$client = new Catapult\Client($cred);


class CoreTest extends PHPUnit_Framework_TestCase {
	public function testURIResource()
	{
		$url = new Catapult\URIResource("http://google.com/");

		$this->assertEquals((string)$url, "http://google.com/");
	}

	public function testEnsureResource()
	{
		$reference = array(); // must be passed by reference
		$data = Catapult\Ensure::Input($reference);

		$this->assertTrue($data instanceof Catapult\DataPacket);
	}

  // constructor 'should' copy an objects state
  // with reference as well as return a new one
  public function testConstructorResource()
  {
    $model = new Catapult\MockModel;
    $model->id = "__TEST__";
    $model1 = Catapult\Constructor::make($model);

    $this->assertEquals($model->id);
  }

  // resolver needs to call create or get
  // depending on what we give as parameters
  public function testResolverResource()
  {
    $model = new Catapult\MockModel;

    // this should be a get
    Resolver:Find($model, "");

    // this should be a post
    Resolver::Find($model, array( "param1" => "value1" )); 
  }
}

?>
