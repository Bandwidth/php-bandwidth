<?php
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Middleware;

class SiteTest extends PHPUnit_Framework_TestCase {
    public static $container;
    public static $sites;
    public static $index = 0;

    public static function setUpBeforeClass() {
        $mock = new MockHandler([
            new Response(201, ['Location' => 'https://api.test.inetwork.com:443/v1.0/accounts/9500249/sites/2489']),
			new Response(200, [], "<?xml version=\"1.0\" encoding=\"UTF-8\" standalone=\"yes\"?><SitesResponse>    <Sites>        <Site>            <Id>2297</Id>            <Name>API Test Site</Name>        </Site>        <Site>            <Id>2301</Id>            <Name>My First Site</Name>            <Description>A Site From Node SDK Examples</Description>        </Site>    </Sites></SitesResponse>"),
			new Response(200, [], "<?xml version=\"1.0\" encoding=\"UTF-8\" standalone=\"yes\"?><SitesResponse>    <Sites>        <Site>            <Id>2297</Id>            <Name>API Test Site</Name>        </Site></Sites></SitesResponse>"),
			new Response(200),
			new Response(200),
            new Response(200),
            new Response(200),
            new Response(200),
            new Response(200, [], "<?xml version=\"1.0\" encoding=\"UTF-8\" standalone=\"yes\"?><SiteTNsResponse>    <SiteTNs>        <TotalCount>4</TotalCount>    </SiteTNs></SiteTNsResponse>"),
            new Response(200, [], "<?xml version=\"1.0\" encoding=\"UTF-8\" standalone=\"yes\"?><LNPResponseWrapper>    <TotalCount>4</TotalCount>    <Links>        <first>Link=&lt;https://api.test.inetwork.com:443/v1.0/accounts/9500249/sites/2297/portins?page=1&amp;size=30&gt;;rel=\"first\";</first>    </Links>    <lnpPortInfoForGivenStatus>        <CountOfTNs>1</CountOfTNs>        <userId>System</userId>        <lastModifiedDate>2015-06-03T15:06:36.234Z</lastModifiedDate>        <OrderDate>2015-06-03T15:06:35.533Z</OrderDate>        <OrderId>535ba91e-5363-474e-8c97-c374a4aa6a02</OrderId>        <OrderType>port_in</OrderType>        <BillingTelephoneNumber>9193491234</BillingTelephoneNumber>        <LNPLosingCarrierId>1537</LNPLosingCarrierId>        <LNPLosingCarrierName>Test Losing Carrier L3</LNPLosingCarrierName>        <ProcessingStatus>SUBMITTED</ProcessingStatus>        <RequestedFOCDate>2015-06-03T15:30:00.000Z</RequestedFOCDate>        <VendorId>49</VendorId>        <VendorName>Bandwidth CLEC</VendorName>        <PON>BWC1433343996123</PON>    </lnpPortInfoForGivenStatus>    <lnpPortInfoForGivenStatus>        <CountOfTNs>1</CountOfTNs>        <userId>byo_dev</userId>        <lastModifiedDate>2015-06-03T15:10:13.384Z</lastModifiedDate>        <OrderDate>2015-06-03T15:10:12.808Z</OrderDate>        <OrderId>98939562-90b0-40e9-8335-5526432d9741</OrderId>        <OrderType>port_in</OrderType>        <BillingTelephoneNumber>7576768750</BillingTelephoneNumber>        <LNPLosingCarrierId>1537</LNPLosingCarrierId>        <LNPLosingCarrierName>Test Losing Carrier L3</LNPLosingCarrierName>        <ProcessingStatus>SUBMITTED</ProcessingStatus>        <RequestedFOCDate>2015-06-03T15:30:00.000Z</RequestedFOCDate>        <VendorId>49</VendorId>        <VendorName>Bandwidth CLEC</VendorName>        <PON>BWC1433344213212</PON>    </lnpPortInfoForGivenStatus></LNPResponseWrapper>"),
        ]);

        self::$container = [];
        $history = Middleware::history(self::$container);
        $handler = HandlerStack::create($mock);
        $handler->push($history);

        $client = new Iris\Client("test", "test", Array('url' => 'https://api.test.inetwork.com/v1.0', 'handler' => $handler));
        $account = new Iris\Account(9500249, $client);
        self::$sites = $account->sites();
    }

    public function testSiteCreate() {
		$site = self::$sites->create(
			array("Name" => "Test Site",
				"Address" => array(
					"City" => "Raleigh",
					"AddressType" => "Service",
					"HouseNumber" => "1",
					"StreetName" => "Avenue",
					"StateCode" => "NC"
			)));

        $this->assertEquals("2489", $site->Id);
        $this->assertEquals("POST", self::$container[self::$index]['request']->getMethod());
        $this->assertEquals("https://api.test.inetwork.com/v1.0/accounts/9500249/sites", self::$container[self::$index]['request']->getUri());
        self::$index++;
    }

	public function testSiteGet() {
		$sites = self::$sites->getList();

        $this->assertEquals(2, count($sites));
		$this->assertEquals("2297", $sites[0]->Id);
        $this->assertEquals("GET", self::$container[self::$index]['request']->getMethod());
        $this->assertEquals("https://api.test.inetwork.com/v1.0/accounts/9500249/sites", self::$container[self::$index]['request']->getUri());
        self::$index++;
    }
	public function testSiteGetOne() {
		$sites = self::$sites->getList();

        $this->assertEquals(1, count($sites));
		$this->assertEquals("2297", $sites[0]->Id);
        $this->assertEquals("GET", self::$container[self::$index]['request']->getMethod());
        $this->assertEquals("https://api.test.inetwork.com/v1.0/accounts/9500249/sites", self::$container[self::$index]['request']->getUri());
        self::$index++;
    }


	public function testSiteUpdate() {
		$site = self::$sites->create(
			array("Id" => "2489"), false
		);

        $site->update();

        $this->assertEquals("PUT", self::$container[self::$index]['request']->getMethod());
        $this->assertEquals("https://api.test.inetwork.com/v1.0/accounts/9500249/sites/2489", self::$container[self::$index]['request']->getUri());
        self::$index++;
    }

	public function testSiteDelete() {
		$site = self::$sites->create(
			array("Id" => "2489"), false
		);

		$site->delete();

		$this->assertEquals("DELETE", self::$container[self::$index]['request']->getMethod());
		$this->assertEquals("https://api.test.inetwork.com/v1.0/accounts/9500249/sites/2489", self::$container[self::$index]['request']->getUri());
		self::$index++;
	}

    public function testSiteOrders() {
		$orders = self::$sites->create(
			array("Id" => "2489"), false
		)->orders()->getList(["status" => "disabled"]);

		$this->assertEquals("GET", self::$container[self::$index]['request']->getMethod());
		$this->assertEquals("https://api.test.inetwork.com/v1.0/accounts/9500249/sites/2489/orders?status=disabled&page=1&size=30", self::$container[self::$index]['request']->getUri());
		self::$index++;
	}

    public function testSiteOrder() {
		$orders = self::$sites->create(
			array("Id" => "2489"), false
		)->orders()->order("1");

		$this->assertEquals("GET", self::$container[self::$index]['request']->getMethod());
		$this->assertEquals("https://api.test.inetwork.com/v1.0/accounts/9500249/sites/2489/orders/1", self::$container[self::$index]['request']->getUri());
		self::$index++;
	}

    public function testSiteOrderTns() {
		$orders = self::$sites->create(
			array("Id" => "2489"), false
		)->orders()->create(["orderId" => "1"], false)->tns()->getList();

		$this->assertEquals("GET", self::$container[self::$index]['request']->getMethod());
		$this->assertEquals("https://api.test.inetwork.com/v1.0/accounts/9500249/sites/2489/orders/1/tns?page=1&size=30", self::$container[self::$index]['request']->getUri());
		self::$index++;
	}

    public function testSiteTotalTns() {
		$count = self::$sites->create(
			array("Id" => "2489"), false
		)->totaltns();

        $this->assertEquals(4, $count);
		$this->assertEquals("GET", self::$container[self::$index]['request']->getMethod());
		$this->assertEquals("https://api.test.inetwork.com/v1.0/accounts/9500249/sites/2489/totaltns", self::$container[self::$index]['request']->getUri());
		self::$index++;
	}

    public function testPortinsGet() {
		$portins = self::$sites->create(
			array("Id" => "2489"), false
		)->portins()->getList(["status" => "x" ]);

		$this->assertEquals(2, count($portins));
		$json = '{"CountOfTNs":"1","lastModifiedDate":"2015-06-03T15:06:36.234Z","OrderDate":"2015-06-03T15:06:35.533Z","OrderType":"port_in","LNPLosingCarrierId":"1537","LNPLosingCarrierName":"Test Losing Carrier L3","RequestedFOCDate":"2015-06-03T15:30:00.000Z","VendorId":"49","VendorName":"Bandwidth CLEC","PON":"BWC1433343996123","OrderId":"535ba91e-5363-474e-8c97-c374a4aa6a02","ProcessingStatus":"SUBMITTED","userId":"System","BillingTelephoneNumber":"9193491234"}';
		$this->assertEquals($json, json_encode($portins[0]->to_array()));
		$this->assertEquals("GET", self::$container[self::$index]['request']->getMethod());
		$this->assertEquals("https://api.test.inetwork.com/v1.0/accounts/9500249/sites/2489/portins?status=x&page=1&size=30", self::$container[self::$index]['request']->getUri());
		self::$index++;
	}


}
