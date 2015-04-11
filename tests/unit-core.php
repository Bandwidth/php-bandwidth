<?php

/**
 * Unit tests for helper functions/utils 
 * 
 * classes tested
 * EnsureResource
 * URIResource
 * ResolverResource
 * utils
 *  
 * TODO needs more testing:
 * should test:
 * loadsResource, schema and meta
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
    $model = new \stdClass;
    $model->schema = array(
        "fields" => array(
          "id"	
        ),
        "needs" => array(
          "id"	
        )
    );
    $model->id = "__TEST__";
    $model1 = Catapult\Constructor::make($model);

    $this->assertEquals($model->id, $model1->id);
  }

  // resolver needs to call create or get
  // depending on what we give as parameters
  public function testResolverResource()
  {
    $model = new \stdClass;
    $model->schema = array(
       "fields" => array(
       	  "id"
       	),
       	"needs" => array(
       	   "id"	
        )
    );
    $model->loads = new \stdClass;
    $model->loads->init = "";
    $model->loads->primary = "GET";
    $model->loads->id = "id";
    
   // TODO test resolver resource here
  }
}

?>
