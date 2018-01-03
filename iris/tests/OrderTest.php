<?php

use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Middleware;

class OrderTest extends PHPUnit_Framework_TestCase {
    public static $container;
    public static $account;
    public static $index = 0;

    public static function setUpBeforeClass() {
        $mock = new MockHandler([
			new Response(200, [], "<?xml version=\"1.0\" encoding=\"UTF-8\" standalone=\"yes\"?><ResponseSelectWrapper>    <ListOrderIdUserIdDate>        <TotalCount>2</TotalCount>        <Links>            <first>Link=&lt;https://api.test.inetwork.com:443/v1.0/accounts/9500249/orders?page=1&amp;size=300&gt;;rel=\"first\";</first>        </Links>        <OrderIdUserIdDate>            <CountOfTNs>0</CountOfTNs>            <CustomerOrderId>123456789</CustomerOrderId>            <userId>byo_dev</userId>            <lastModifiedDate>2015-06-13T16:14:46.017Z</lastModifiedDate>            <OrderDate>2015-06-13T16:14:45.956Z</OrderDate>            <OrderType>new_number</OrderType>            <orderId>016c1aef-a873-4a90-8374-60771cba9452</orderId>            <OrderStatus>FAILED</OrderStatus>        </OrderIdUserIdDate>        <OrderIdUserIdDate>            <CountOfTNs>0</CountOfTNs>            <CustomerOrderId>123456789</CustomerOrderId>            <userId>byo_dev</userId>            <lastModifiedDate>2015-06-13T16:32:04.216Z</lastModifiedDate>            <OrderDate>2015-06-13T16:32:04.181Z</OrderDate>            <OrderType>new_number</OrderType>            <orderId>77659f47-d527-42ad-bf72-34b6841016ac</orderId>            <OrderStatus>FAILED</OrderStatus>        </OrderIdUserIdDate>    </ListOrderIdUserIdDate></ResponseSelectWrapper>"),
			new Response(200, [], "<?xml version=\"1.0\" encoding=\"UTF-8\" standalone=\"yes\"?><ResponseSelectWrapper>    <ListOrderIdUserIdDate>        <TotalCount>1</TotalCount>        <Links>            <first>Link=&lt;https://api.test.inetwork.com:443/v1.0/accounts/9500249/orders?page=1&amp;size=300&gt;;rel=\"first\";</first>        </Links>        <OrderIdUserIdDate>            <CountOfTNs>0</CountOfTNs>            <CustomerOrderId>123456789</CustomerOrderId>            <userId>byo_dev</userId>            <lastModifiedDate>2015-06-13T16:14:46.017Z</lastModifiedDate>            <OrderDate>2015-06-13T16:14:45.956Z</OrderDate>            <OrderType>new_number</OrderType>            <orderId>016c1aef-a873-4a90-8374-60771cba9452</orderId>            <OrderStatus>FAILED</OrderStatus>        </OrderIdUserIdDate>    </ListOrderIdUserIdDate></ResponseSelectWrapper>"),
			new Response(200, [], "<?xml version=\"1.0\" encoding=\"UTF-8\" standalone=\"yes\"?><ResponseSelectWrapper>    <ListOrderIdUserIdDate>        <TotalCount>0</TotalCount>        <Links>            <first>Link=&lt;https://api.test.inetwork.com:443/v1.0/accounts/9500249/orders?page=1&amp;size=300&gt;;rel=\"first\";</first>        </Links>    </ListOrderIdUserIdDate></ResponseSelectWrapper>"),
			new Response(200, [], "<?xml version=\"1.0\" encoding=\"UTF-8\" standalone=\"yes\"?><OrderResponse>    <Order>        <CustomerOrderId>123456789</CustomerOrderId>        <Name>Available Telephone Number order</Name>        <OrderCreateDate>2015-06-20T10:54:08.042Z</OrderCreateDate>        <BackOrderRequested>false</BackOrderRequested>        <id>f30a31a1-1de4-4939-b094-4521bbe5c8df</id>        <ExistingTelephoneNumberOrderType>            <TelephoneNumberList>                <TelephoneNumber>9193752369</TelephoneNumber>                <TelephoneNumber>9193752720</TelephoneNumber>                <TelephoneNumber>9193752648</TelephoneNumber>            </TelephoneNumberList>        </ExistingTelephoneNumberOrderType>        <PartialAllowed>true</PartialAllowed>        <SiteId>2297</SiteId>    </Order>    <OrderStatus>RECEIVED</OrderStatus></OrderResponse>"),
			new Response(200, [], "<?xml version=\"1.0\" encoding=\"UTF-8\" standalone=\"yes\"?><OrderResponse>    <CompletedQuantity>0</CompletedQuantity>    <CreatedByUser>byo_dev</CreatedByUser>    <ErrorList>        <Error>            <Code>5005</Code>            <Description>The telephone number is unavailable for ordering</Description>            <TelephoneNumber>9193752369</TelephoneNumber>        </Error>        <Error>            <Code>5005</Code>            <Description>The telephone number is unavailable for ordering</Description>            <TelephoneNumber>9193752648</TelephoneNumber>        </Error>        <Error>            <Code>5005</Code>            <Description>The telephone number is unavailable for ordering</Description>            <TelephoneNumber>9193752720</TelephoneNumber>        </Error>    </ErrorList>    <FailedNumbers>        <FullNumber>9193752369</FullNumber>        <FullNumber>9193752648</FullNumber>        <FullNumber>9193752720</FullNumber>    </FailedNumbers>    <LastModifiedDate>2015-06-20T10:54:08.094Z</LastModifiedDate>    <OrderCompleteDate>2015-06-20T10:54:08.094Z</OrderCompleteDate>    <Order>        <CustomerOrderId>123456789</CustomerOrderId>        <Name>Available Telephone Number order</Name>        <OrderCreateDate>2015-06-20T10:54:08.042Z</OrderCreateDate>        <PeerId>500709</PeerId>        <BackOrderRequested>false</BackOrderRequested>        <ExistingTelephoneNumberOrderType/>        <PartialAllowed>true</PartialAllowed>        <SiteId>2297</SiteId>    </Order>    <OrderStatus>FAILED</OrderStatus>    <FailedQuantity>3</FailedQuantity></OrderResponse>"),
			new Response(200, [], ""),
			new Response(200, [], ""),
			new Response(200, [], ""),
			new Response(200, [], ""),
            new Response(200, [], ""),
        ]);

        self::$container = [];
        $history = Middleware::history(self::$container);
        $handler = HandlerStack::create($mock);
        $handler->push($history);

        $client = new Iris\Client("test", "test", Array('url' => 'https://api.test.inetwork.com/v1.0', 'handler' => $handler));
        self::$account = new Iris\Account(9500249, $client);
    }

    public function testGetOrders() {
        $orders = self::$account->orders()->getList();

        $this->assertEquals(2, count($orders));
		$this->assertEquals("016c1aef-a873-4a90-8374-60771cba9452", $orders[0]->get_id());
        $this->assertEquals("GET", self::$container[self::$index]['request']->getMethod());
        $this->assertEquals("https://api.test.inetwork.com/v1.0/accounts/9500249/orders?page=1&size=30", self::$container[self::$index]['request']->getUri());
        self::$index++;
    }
	public function testGetOrdersSingle() {
		$orders = self::$account->orders()->getList();

		$this->assertEquals(1, count($orders));
		$this->assertEquals("016c1aef-a873-4a90-8374-60771cba9452", $orders[0]->get_id());
		$this->assertEquals("GET", self::$container[self::$index]['request']->getMethod());
		$this->assertEquals("https://api.test.inetwork.com/v1.0/accounts/9500249/orders?page=1&size=30", self::$container[self::$index]['request']->getUri());
		self::$index++;
	}
	public function testGetOrdersEmpty() {
		$orders = self::$account->orders()->getList();

		$this->assertEquals(0, count($orders));
		$this->assertEquals("GET", self::$container[self::$index]['request']->getMethod());
		$this->assertEquals("https://api.test.inetwork.com/v1.0/accounts/9500249/orders?page=1&size=30", self::$container[self::$index]['request']->getUri());
		self::$index++;
	}

	public function testOrderCreate() {
		$order = self::$account->orders()->create([
			"Name" => "Available Telephone Number order",
			"SiteId" => "2297",
			"CustomerOrderId" => "123456789",
			"ExistingTelephoneNumberOrderType" => [
				"TelephoneNumberList" => [
					"TelephoneNumber" => [ "9193752369", "9193752720", "9193752648"]
				]
			]
		]);
		$this->assertEquals("f30a31a1-1de4-4939-b094-4521bbe5c8df", $order->get_id());
		$this->assertEquals("RECEIVED", $order->OrderStatus);
		$this->assertEquals("POST", self::$container[self::$index]['request']->getMethod());
		$this->assertEquals("https://api.test.inetwork.com/v1.0/accounts/9500249/orders", self::$container[self::$index]['request']->getUri());
		self::$index++;
	}
	public function testOrderGet() {
		$response = self::$account->orders()->order("f30a31a1-1de4-4939-b094-4521bbe5c8df", true);

		$this->assertEquals("f30a31a1-1de4-4939-b094-4521bbe5c8df", $response->Order->get_id());
		$this->assertEquals("GET", self::$container[self::$index]['request']->getMethod());
		$this->assertEquals("https://api.test.inetwork.com/v1.0/accounts/9500249/orders/f30a31a1-1de4-4939-b094-4521bbe5c8df?tndetail=true", self::$container[self::$index]['request']->getUri());
		self::$index++;
	}

	public function testOrderPostArea() {
		$order = self::$account->orders()->create([
			"Name" => "Available Telephone Number order",
			"SiteId" => "2297",
			"CustomerOrderId" => "123456789",
			"BackOrderRequested" => "false",
			"AreaCodeSearchAndOrderType" => [
				"AreaCode" => "617",
				"Quantity" => "1"
			]
		], false);

		$json = '{"Name":"Available Telephone Number order","CustomerOrderId":"123456789","SiteId":"2297","BackOrderRequested":"false","AreaCodeSearchAndOrderType":{"AreaCode":"617","Quantity":"1"}}';

		$this->assertEquals($json, json_encode($order->to_array()));
	}

	public function testOrderRateCenter() {
		$order = self::$account->orders()->create([
			"Name" => "Available Telephone Number order",
			"SiteId" => "2297",
			"CustomerOrderId" => "123456789",
			"BackOrderRequested" => "false",
			"RateCenterSearchAndOrderType" => [
				"RateCenter" => "RALEIGH",
				"State" => "NC",
				"Quantity" => "1"
			]
		], false);

		$json = '{"Name":"Available Telephone Number order","CustomerOrderId":"123456789","SiteId":"2297","BackOrderRequested":"false","RateCenterSearchAndOrderType":{"RateCenter":"RALEIGH","State":"NC","Quantity":"1"}}';

		$this->assertEquals($json, json_encode($order->to_array()));
	}

	public function testOrderNpaNxx() {
		$order = self::$account->orders()->create([
			"Name" => "Available Telephone Number order",
			"SiteId" => "2297",
			"CustomerOrderId" => "123456789",
			"BackOrderRequested" => "false",
			"NPANXXSearchAndOrderType" => [
				"NpaNxx" => "919439",
				"EnableTNDetail" => "true",
				"EnableLCA" => "false",
				"Quantity" => "1"
			]
		], false);

		$json = '{"Name":"Available Telephone Number order","CustomerOrderId":"123456789","SiteId":"2297","BackOrderRequested":"false","NPANXXSearchAndOrderType":{"NpaNxx":"919439","EnableTNDetail":"true","EnableLCA":"false","Quantity":"1"}}';

		$this->assertEquals($json, json_encode($order->to_array()));
	}
	public function testOrderTollFree() {
		$order = self::$account->orders()->create([
			"Name" => "Available Telephone Number order",
			"SiteId" => "2297",
			"CustomerOrderId" => "123456789",
			"BackOrderRequested" => "false",
			"TollFreeVanitySearchAndOrderType" => [
				"TollFreeVanity" => "newcars",
				"Quantity" => "1"
			]
		], false);

		$json = '{"Name":"Available Telephone Number order","CustomerOrderId":"123456789","SiteId":"2297","BackOrderRequested":"false","TollFreeVanitySearchAndOrderType":{"TollFreeVanity":"newcars","Quantity":"1"}}';

		$this->assertEquals($json, json_encode($order->to_array()));
	}
	public function testOrderTollWildCard() {
		$order = self::$account->orders()->create([
			"Name" => "Available Telephone Number order",
			"SiteId" => "2297",
			"CustomerOrderId" => "123456789",
			"BackOrderRequested" => "false",
			"TollFreeWildCharSearchAndOrderType" => [
				"TollFreeWildCardPattern" => "8**",
				"Quantity" => "1"
			]
		], false);

		$json = '{"Name":"Available Telephone Number order","CustomerOrderId":"123456789","SiteId":"2297","BackOrderRequested":"false","TollFreeWildCharSearchAndOrderType":{"Quantity":"1","TollFreeWildCardPattern":"8**"}}';

		$this->assertEquals($json, json_encode($order->to_array()));
	}
	public function testOrderState() {
		$order = self::$account->orders()->create([
			"Name" => "Available Telephone Number order",
			"SiteId" => "2297",
			"CustomerOrderId" => "123456789",
			"BackOrderRequested" => "false",
			"StateSearchAndOrderType" => [
				"State" => "NC",
				"Quantity" => "1"
			]
		], false);

		$json = '{"Name":"Available Telephone Number order","CustomerOrderId":"123456789","SiteId":"2297","BackOrderRequested":"false","StateSearchAndOrderType":{"State":"NC","Quantity":"1"}}';

		$this->assertEquals($json, json_encode($order->to_array()));
	}
	public function testOrderCity() {
		$order = self::$account->orders()->create([
			"Name" => "Available Telephone Number order",
			"SiteId" => "2297",
			"CustomerOrderId" => "123456789",
			"BackOrderRequested" => "false",
			"CitySearchAndOrderType" => [
				"State" => "NC",
				"City" => "RALEIGH",
				"Quantity" => "1"
			]
		], false);

		$json = '{"Name":"Available Telephone Number order","CustomerOrderId":"123456789","SiteId":"2297","BackOrderRequested":"false","CitySearchAndOrderType":{"State":"NC","City":"RALEIGH","Quantity":"1"}}';

		$this->assertEquals($json, json_encode($order->to_array()));
	}
	public function testOrderZip() {
		$order = self::$account->orders()->create([
			"Name" => "Available Telephone Number order",
			"SiteId" => "2297",
			"CustomerOrderId" => "123456789",
			"BackOrderRequested" => "false",
			"CitySearchAndOrderType" => [
				"Zip" => "27606",
				"Quantity" => "1"
			]
		], false);

		$json = '{"Name":"Available Telephone Number order","CustomerOrderId":"123456789","SiteId":"2297","BackOrderRequested":"false","CitySearchAndOrderType":{"Quantity":"1","Zip":"27606"}}';

		$this->assertEquals($json, json_encode($order->to_array()));
	}
	public function testOrderLata() {
		$order = self::$account->orders()->create([
			"Name" => "Available Telephone Number order",
			"SiteId" => "2297",
			"CustomerOrderId" => "123456789",
			"BackOrderRequested" => "false",
			"LATASearchAndOrderType" => [
				"Lata" => "224",
				"Quantity" => "1"
			]
		], false);

		$json = '{"Name":"Available Telephone Number order","CustomerOrderId":"123456789","SiteId":"2297","BackOrderRequested":"false","LATASearchAndOrderType":{"Quantity":"1","Lata":"224"}}';

		$this->assertEquals($json, json_encode($order->to_array()));
	}

	public function testOrderPost() {
		$order = self::$account->orders()->create([
			"id" => "f30a31a1-1de4-4939-b094-4521bbe5c8df",
			"Name" => "Available Telephone Number order",
			"CustomerOrderId" => "123456789",
			"CloseOrder" => "true"
		], false);

		$order->update();

		$json = '{"id":"f30a31a1-1de4-4939-b094-4521bbe5c8df","Name":"Available Telephone Number order","CustomerOrderId":"123456789","CloseOrder":"true"}';

		$this->assertEquals($json, json_encode($order->to_array()));
		$this->assertEquals("PUT", self::$container[self::$index]['request']->getMethod());
		$this->assertEquals("https://api.test.inetwork.com/v1.0/accounts/9500249/orders/f30a31a1-1de4-4939-b094-4521bbe5c8df", self::$container[self::$index]['request']->getUri());
		self::$index++;
	}

    public function testReservedPost() {
        $order = self::$account->orders()->create([
			"Name" => "Available Telephone Number order",
            "SiteId" => "385",
			"CustomerOrderId" => "123456789",
            "ExistingTelephoneNumberOrderType" => [
				"TelephoneNumberList" => [
					"TelephoneNumber" => [ "9193752369", "9193752720", "9193752648"]
				],
                "ReservationIdList" => [
                    "ReservationId" => [ "1", "2" ]
                ]
			]
		], false);

        $json = '{"Name":"Available Telephone Number order","CustomerOrderId":"123456789","SiteId":"385","ExistingTelephoneNumberOrderType":{"TelephoneNumberList":{"TelephoneNumber":["9193752369","9193752720","9193752648"]},"ReservationIdList":{"ReservationId":["1","2"]}}}';

		$this->assertEquals($json, json_encode($order->to_array()));
    }

    public function testOrderTns() {
		self::$account->orders()->create([
			"id" => "f30a31a1-1de4-4939-b094-4521bbe5c8df"
		], false)->tns()->getList();

		$this->assertEquals("GET", self::$container[self::$index]['request']->getMethod());
		$this->assertEquals("https://api.test.inetwork.com/v1.0/accounts/9500249/orders/f30a31a1-1de4-4939-b094-4521bbe5c8df/tns?page=1&size=30", self::$container[self::$index]['request']->getUri());
		self::$index++;
	}


}
