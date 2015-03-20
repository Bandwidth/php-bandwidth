<?php

/**
 * Unit test Endpoints
 *
 * These functions will test the following
 * functions
 *
 * commands tested:
 * create/1
 * create/2
 * update/1
 * listAll/1
 * getCredentials/1
 *
 * Things to consider:
 * endpoint names need to be valid and short
 */

$cred = new Catapult\Credentials;
$client = new Catapult\Client($cred);

final class EndpointsTest extends PHPUnit_Framework_TestCase {
  public function setUp() {
    $this->toDelete = array(); 
  }
  public function testEndpointsCreate() {
    $params = new Catapult\Parameters;
    $params1 = new Catapult\Parameters;
    $endpoint = new Catapult\Endpoints;
    $domain = new Catapult\Domains;  
     
    $params->setName("A-d-e-1"); 
    $params->setDescription("a domain description");
    $domain->create($params);
    $this->toDelete[] = $domain;
 
    $params1->setName("A-e-1"); 
    $params1->setDescription("a endpoints description");
    $params1->setCredentials(array( 
      "username" => "endpointsUser",
      "password" => "endpointsPassword",
      "realm" => "domainname.app.bwapp.io"
    ));
    $params1->setDomainId($domain->id);
    $endpoint->create($params1);
  }

  public function testEndpointsGet() {
    $params = new Catapult\Parameters;
    $params1 = new Catapult\Parameters;
    $domain = new Catapult\Domains;
    $endpoint = new Catapult\Endpoints;
    $params->setName("A-d-e-2");
    $params->setDescription("A domain description");

    $domain->create($params);
    $this->toDelete[] = $domain;
    $params1->setName("A-e-2");
    $params1->setDescription("an endpoints description");
    $params1->setCredentials(array(
      "username"=>" endpointsUser", 
      "password" => "endpointsPassword", 
      "realm" => "domainanme.applicationname.bwapp.io"
    ));
    $params1->setDomainId($domain->id);
    $endpoint->create($params1); 
    $id = $endpoint->id;

    $endpointsGet = new Catapult\Endpoints($domain->id, $endpoint->id);

    $this->assertEquals($endpointsGet->id, $endpoint->id);
     
  }
    
  public function testEndpointsMulti() {
    $params = new Catapult\Parameters;
    $domain = new Catapult\Domains;

    $params->setName("A-d-e-3");
    $params->setDescription("a domains description");
    $domain->create($params);
    $this->toDelete[] = $domain;

    $endpointMulti = new Catapult\EndpointsMulti($domain->id);

    for ($i = 0; $i != 10; $i ++ ) {
      $endpointMulti->pushEndpoint(array(
        "name" => "A-e-m-" . $i,
        "description" => "a description",
        "credentials" => array(
          "username" => "endpointsUsername" . $i, 
          "password" => "endpointsPassword" . $i,
          "realm" => "endpointsRealm" . $i
        )
      ));
    }
    $endpointMulti->execute();

    $this->assertEquals($endpointMulti->done, true);
  }

  public function testEndpointsGetCredentials() {
    $params = new Catapult\Parameters; 
    $params1 = new Catapult\Parameters;
    $domain = new Catapult\Domains;
    $endpoints = new Catapult\Endpoints;
    
    $params->setName("A-d-5"); 
    $params->setDescription("A domains description");
    $domain->create($params);

    $this->toDelete[] = $domain;

    $params1->setName("A-d-e-5");
    $params1->setDescription("a endpoints description");
    $params1->setCredentials(array(
      "username" => "anEndpointsUser",
      "password" => "anEndpointsPassword",
      "realm" => "anEndpointsRealm"
    ));
    $params1->setDomainId($domain->id);

    $endpoints->create($params1);
   
    $endpoint = new Catapult\Endpoints($domain->id, $endpoints->id); 
    $creds = $endpoint->getCredentials();
    // needs added testing
    //
    $this->assertTrue($creds instanceof Catapult\EndpointsCredentials);
  }

  public function testEndpointsUpdate() {
    $params = new Catapult\Parameters;
    $params2 = new Catapult\Parameters;
    $params3 = new Catapult\Parameters;
    $domain = new Catapult\Domains;
    $endpoint = new Catapult\Endpoints;

    $params->setName("A-d-e-6");
    $params->setDescription("A domains description");
    $domain->create($params);

    $this->toDelete[] = $domain;

    $params2->setName("A-e-6");
    $params2->setDescription("An endpoints description");

    $params2->setCredentials(array(
        "username" => "endpointsUser",
        "password" => "endpointsPassword",
        "realm" => "endpointsSipRealm"
     ));

    $params2->setDomainId($domain->id);
    

    $endpoint->create($params2);

    $params3->setDescription("Updated endpoints description");
    $endpoint->update( $params3 );

    $this->assertEquals($endpoint->description,"Updated endpoints description");
  }

  public function testEndpointsDelete() {
    $params = new Catapult\Parameters;
    $params1 = new Catapult\Parameters;
    $domain = new Catapult\Domains;
    $endpoint = new Catapult\Endpoints;
      
    $params->setName("A-d-e-7");
    $params->setDescription("A domains description");

    $domain->create($params);
    $this->toDelete[] = $domain;

    

    $params1->setName("A-e-7");
    $params1->setDescription("an endpoints description");
    $params1->setCredentials(array(
      "username" => "aUsername",
      "password" => "aPassword",
      "realm" => "aRealm"
    ));
    $params1->setDomainId($domain->id);

    $endpoint->create($params1);   
    
    $id = $endpoint->id;
    $endpoint->delete(); 
    $endpoints = $domain->listEndpoints();
    $endpoints->find(array("id" => $id));
      
    $this->assertTrue($endpoints->isEmpty());
  }

  public function tearDown() {
    foreach ($this->toDelete as $domain) {
      $endpoints = $domain->listEndpoints();
      foreach ($endpoints->get() as $e) {
        $e->delete();
      }
  
      $domain->delete();
    }
  }


}
