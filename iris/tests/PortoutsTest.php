<?php

// use GuzzleHttp\Handler\MockHandler;
// use GuzzleHttp\HandlerStack;
// use GuzzleHttp\Psr7\Response;
// use GuzzleHttp\Middleware;
//
// class PortoutsTest extends PHPUnit_Framework_TestCase {
// 	public static $container;
//     public static $portouts;
//     public static $index = 0;
//
//     public static function setUpBeforeClass() {
//         $mock = new MockHandler([
//         ]);
//
//         self::$container = [];
//         $history = Middleware::history(self::$container);
//         $handler = HandlerStack::create($mock);
//         $handler->push($history);
//
//         $client = new Iris\Client("test", "test", Array('url' => 'https://api.test.inetwork.com/v1.0', 'handler' => $handler));
//         $account = new Iris\Account(9500249, $client);
//         self::$portouts = $account->portouts();
//     }
//     public function testPortoutsGet() {
// 		$portouts = self::$portouts->get(["status" => "x" ]);
//
// 		$this->assertEquals(2, count($portouts));
// 		$json = '{"CountOfTNs":"1","lastModifiedDate":"2015-06-03T15:06:36.234Z","OrderDate":"2015-06-03T15:06:35.533Z","OrderType":"port_in","LNPLosingCarrierId":"1537","LNPLosingCarrierName":"Test Losing Carrier L3","RequestedFOCDate":"2015-06-03T15:30:00.000Z","VendorId":"49","VendorName":"Bandwidth CLEC","PON":"BWC1433343996123","OrderId":"535ba91e-5363-474e-8c97-c374a4aa6a02","ProcessingStatus":"SUBMITTED","userId":"System","BillingTelephoneNumber":"9193491234"}';
// 		$this->assertEquals($json, json_encode($portouts[0]->to_array()));
// 		$this->assertEquals("GET", self::$container[self::$index]['request']->getMethod());
// 		$this->assertEquals("https://api.test.inetwork.com/v1.0/accounts/9500249/portouts?status=x&page=1&size=30", self::$container[self::$index]['request']->getUri());
// 		self::$index++;
// 	}
// }
