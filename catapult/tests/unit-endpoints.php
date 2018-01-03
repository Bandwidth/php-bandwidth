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
    $this->toDelete = $this->toDeleteEP = array();
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
      "password" => "endpointsPassword"
    ));
    $params1->setDomainId($domain->id);
    $endpoint->create($params1);
    $this->toDeleteEP[] = $endpoint;
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
      "password" => "endpointsPassword"
    ));
    $params1->setDomainId($domain->id);
    $endpoint->create($params1);
    $this->toDeleteEP[] = $endpoint;
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
          "password" => "endpointsPassword" . $i
        )
      ));
    }
    $created = $endpointMulti->execute();
    $this->toDeleteEP = $created;

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
      "password" => "anEndpointsPassword"
    ));
    $params1->setDomainId($domain->id);

    $endpoints->create($params1);
    $this->toDeleteEP[] = $endpoints;
   
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
        "password" => "endpointsPassword"
     ));

    $params2->setDomainId($domain->id);
    

    $endpoint->create($params2);
    $this->toDeleteEP[] = $endpoint;

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
      "password" => "aPassword"
    ));
    $params1->setDomainId($domain->id);

    $endpoint->create($params1);   
    
    $id = $endpoint->id;
    $endpoint->delete(); 
    $endpoints = $domain->listEndpoints();
    $endpoints->find(array("id" => $id));
      
    $this->assertTrue($endpoints->isEmpty());
  }

  public function testEndpointsCreateAuthToken() {
    $params = new Catapult\Parameters;
    $params1 = new Catapult\Parameters;
    $domain = new Catapult\Domains;
    $endpoints = new Catapult\Endpoints;

    $params->setName("A-d-8");
    $params->setDescription("A domains description");
    $domain->create($params);

    $this->toDelete[] = $domain;

    $params1->setName("A-d-e-8");
    $params1->setDescription("a endpoints description");
    $params1->setCredentials(array(
      "password" => "anEndpointsPassword"
    ));
    $params1->setDomainId($domain->id);

    $endpoints->create($params1);
    $this->toDeleteEP[] = $endpoints;

    $endpoint = new Catapult\Endpoints($domain->id, $endpoints->id);
    $token = $endpoint->createAuthToken();
    $this->assertTrue($token instanceof Catapult\EndpointsToken);
	$this->assertTrue(!empty($token->token));
	$this->assertTrue(!empty($token->expires));
  }

  public function testEndpointsDeleteAuthToken() {
    $params = new Catapult\Parameters;
    $params1 = new Catapult\Parameters;
    $domain = new Catapult\Domains;
    $endpoints = new Catapult\Endpoints;

    $params->setName("A-d-9");
    $params->setDescription("A domains description");
    $domain->create($params);

    $this->toDelete[] = $domain;

    $params1->setName("A-d-e-9");
    $params1->setDescription("a endpoints description");
    $params1->setCredentials(array(
      "password" => "anEndpointsPassword"
    ));
    $params1->setDomainId($domain->id);

    $endpoints->create($params1);
    $this->toDeleteEP[] = $endpoints;

    $endpoint = new Catapult\Endpoints($domain->id, $endpoints->id);
    $token = $endpoint->createAuthToken();
    $delete = $endpoint->deleteAuthToken($token);
	$this->assertTrue(!empty($delete));
  }

  public function tearDown() {
    foreach ($this->toDeleteEP as $endpoint) {
      $endpoint->delete();
    }

    foreach ($this->toDelete as $domain) {
      $domain->delete();
    }
  }


}
