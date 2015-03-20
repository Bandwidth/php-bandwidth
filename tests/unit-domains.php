<?php

/* Unit tests domains 
 *
 * These will test the following
 * functions
 * 
 * commands tested:
 * create/1
 * update/1
 * delete/1
 * listEndpoints/1
 * listAll/1
 *
 * Things to check:
 * domain names need to be less than
 * 16 characters and unique so we use abbreviations
 * use tearDown so we can run again later
 */
$cred = new Catapult\Credentials;
$client = new Catapult\Client($cred);

final class DomainsTest extends PHPUnit_Framework_TestCase {

  public function testDomainCreate() {
    $params = new Catapult\Parameters;
    $params->setName("A-t");
    $params->setDescription("A domains description");
    $domain = new Catapult\Domains($params);

    $this->assertEquals($domain->name, "A-t");
  }

  public function testDomainGet() {
    $params = new Catapult\Parameters;
    $params->setName("A-d-t-2");
    $params->setDescription("A domains description");
    $domain = new Catapult\Domains($params);
    $id = $domain->id; 
    $domainGet  = new Catapult\Domains($domain->id);

    $this->assertEquals($domain->id, $domainGet->id);
  }
  // should be more that one by now
  public function testDomainListAll() {
    $domains = new Catapult\DomainsCollection;

    $domains = $domains->listAll();
    $this->assertTrue(!$domains->isEmpty());
  }

  public function testDomainsListEndpoints() {
    $params = new Catapult\Parameters;
    $params->setName("A-d-t-e");
    $params->setDescription("A domains endpoints test");

    $domain = new Catapult\Domains($params);
    $endpoints = $domain->listEndpoints();

    $this->assertTrue($endpoints->isEmpty());
  }

  public function testDomainsDescriptionUpdate() {
    $params = new Catapult\Parameters;
    $params2 = new Catapult\Parameters;
    $domains = new Catapult\Domains;    
    $params->setName("A-d-t-3");
    $params->setDescription("initial description"); 

    $domains->create($params);

    $params2->setDescription("updated description");
    $domains->update($params2);

    $this->assertEquals($domains->description, "updated description");
  }

  public function testDomainsDelete() {
    $params = new Catapult\Parameters;
    $domains = new Catapult\Domains;
    $params->setName("A-d-t-4");
    $domains->create($params);
    $id = $domains->id; 

    $domains->delete();

    // look it up
    $domains = new Catapult\DomainsCollection;
    $domains = $domains->listAll()->find(array("id" => $id));

    $this->assertTrue($domains->isEmpty());
  }

  public function tearDown() {
    $deletes = array("A-d-t-4", "A-d-t-2", "A-t", "A-d-t-3", "A-d-t-e");
    $domains = new Catapult\DomainsCollection;
    foreach ($domains->listAll(array("size"=>1000))->get() as $domain) {
      if (in_array($domain->name, $deletes)) {
        $domain->delete();
      }
    }

  }
}
