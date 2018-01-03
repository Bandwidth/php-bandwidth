<?php

/**
 * Unit tests for collections
 *
 * these need to:
 * listAll: list set of models
 * listIterator: provide an iterative lister
 * find: function to find things in collections
 * first: subsequence first result
 * last: subsequence last request
 * toArray collection to array
 *  
 * use Calls as our generic listing collection
 *
 * 
 * TODO collection testing needs to be expanded on
 */


$cred = new Catapult\Credentials;
$client = new Catapult\Client($cred);

class CollectionsTest extends PHPUnit_Framework_TestCase {
  public function testListAll() {
    $calls = new Catapult\CallCollection;
    $calls = $calls->listAll();

    $this->assertTrue($calls instanceof Catapult\CallCollection);
  }
  public function testListIterator() {
    $calls = new Catapult\CallCollection;
    $callIterator = $calls->listIterator();
    $this->assertTrue($callIterator instanceof Catapult\CollectionIterator); 
    $this->assertTrue(is_array($callIterator()));
  }
  public function testToArray() {
    $calls = new Catapult\CallCollection;
    $callsArray = $calls->listAll()->toArray();
    $this->assertTrue(is_array($callsArray));
  }
  public function testReload() {
    $calls = new Catapult\CallCollection;
    $calls1 = $calls->listAll()->get();
    // sizes should be the same 
    $size1 = count($calls1);
    $calls2 = $calls->reload()->get();
    
    $size2 = count($calls1);
    $this->assertEquals($size1, $size2);
  }
  // needs to always get the first
  public function testFirst() {
    $calls = new  Catapult\CallCollection;
    $calls->listAll();
    $first = $calls->first();
    $firstCall = $calls->get()[0];
    $this->assertEquals($firstCall->id, $first->id);
  }
  public function testLast() {
    $calls = new Catapult\CallCollection;
    $calls->listAll();
    $last = $calls->last();
    $countOfCalls = count($calls->get());
    $lastCall = $calls->get()[$countOfCalls];
    $this->assertEquals($lastCall->id, $last->id);
  }
  public function testFind() {
    $calls = new Catapult\CallCollection;
    // mock here
    $firstCall = $calls->listAll()->first();
    $subCollection=  $calls->listAll()->find(
      array("from" => $firstCall->from
    ));
    $this->assertTrue(count($subCollection) ==1);
  }
  
}
