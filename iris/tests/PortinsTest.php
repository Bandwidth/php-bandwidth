<?php

use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Middleware;

class PortinsTest extends PHPUnit_Framework_TestCase {
	public static $container;
    public static $portins;
    public static $index = 0;

    public static function setUpBeforeClass() {
        $mock = new MockHandler([
			new Response(200, [], "<?xml version=\"1.0\" encoding=\"UTF-8\" standalone=\"yes\"?><LnpOrderResponse><OrderId>d28b36f7-fa96-49eb-9556-a40fca49f7c6</OrderId><Status><Code>201</Code><Description>Order request received. Please use the order id to check the status of your order later.</Description></Status><ProcessingStatus>PENDING_DOCUMENTS</ProcessingStatus><LoaAuthorizingPerson>John Doe</LoaAuthorizingPerson><Subscriber><SubscriberType>BUSINESS</SubscriberType><BusinessName>Acme Corporation</BusinessName><ServiceAddress><HouseNumber>1623</HouseNumber><StreetName>Brockton Ave #1</StreetName><City>Los Angeles</City><StateCode>CA</StateCode><Zip>90025</Zip><Country>USA</Country></ServiceAddress></Subscriber><BillingTelephoneNumber>6882015002</BillingTelephoneNumber><ListOfPhoneNumbers><PhoneNumber>6882015025</PhoneNumber><PhoneNumber>6882015026</PhoneNumber></ListOfPhoneNumbers><Triggered>false</Triggered><BillingType>PORTIN</BillingType></LnpOrderResponse>"),
			new Response(200),
			new Response(200, [], "<?xml version=\"1.0\" encoding=\"UTF-8\" standalone=\"yes\"?><FileMetaData><DocumentName>test.txt</DocumentName><DocumentType>LOA</DocumentType></FileMetaData>"),
			new Response(200),
			new Response(200),
			new Response(200, [], "<?xml version=\"1.0\" encoding=\"UTF-8\" standalone=\"yes\"?>    <fileListResponse>        <fileCount>0</fileCount>        <resultCode>0</resultCode>        <resultMessage>No LOA files found for order</resultMessage>    </fileListResponse>"),
			new Response(200),
            new Response(200, [], "<?xml version=\"1.0\" encoding=\"UTF-8\" standalone=\"yes\"?><ActivationStatusResponse>    <ActivationStatus>        <AutoActivationDate>2014-08-29T18:30:00+03:00</AutoActivationDate>        <ActivatedTelephoneNumbersList>            <TelephoneNumber>6052609021</TelephoneNumber>            <TelephoneNumber>6052609021</TelephoneNumber>        </ActivatedTelephoneNumbersList>        <NotYetActivatedTelephoneNumbersList/>    </ActivationStatus></ActivationStatusResponse>"),
			new Response(200, [], "<?xml version=\"1.0\" encoding=\"UTF-8\" standalone=\"yes\"?><ActivationStatusResponse>    <ActivationStatus>        <AutoActivationDate>2014-08-29T18:30:00+03:00</AutoActivationDate>        <ActivatedTelephoneNumbersList>            <TelephoneNumber>6052609021</TelephoneNumber>            <TelephoneNumber>6052609021</TelephoneNumber>        </ActivatedTelephoneNumbersList>        <NotYetActivatedTelephoneNumbersList/>    </ActivationStatus></ActivationStatusResponse>"),
			new Response(200, [], "<?xml version=\"1.0\"?> <LnpOrderResponse><OrderId>0fe651a2-6ffc-4758-b7b7-e3eed66409ec</OrderId> <Status><Code>200</Code><Description>Supp request received. Please use the order id to check the status of your order later.</Description></Status><ProcessingStatus>REQUESTED_SUPP</ProcessingStatus><RequestedFocDate>2012-08-30T00:00:00Z</RequestedFocDate> </LnpOrderResponse>"),
			new Response(200, [], "<?xml version=\"1.0\" encoding=\"UTF-8\" standalone=\"yes\"?><LNPResponseWrapper>    <TotalCount>2</TotalCount>    <Links>        <first>Link=&lt;https://api.test.inetwork.com:443/v1.0/accounts/9500249/portins?page=1&amp;size=300&gt;;rel=\"first\";</first>    </Links>    <lnpPortInfoForGivenStatus>        <CountOfTNs>1</CountOfTNs>        <userId>System</userId>        <lastModifiedDate>2015-06-03T15:06:36.234Z</lastModifiedDate>        <OrderDate>2015-06-03T15:06:35.533Z</OrderDate>        <OrderId>535ba91e-5363-474e-8c97-c374a4aa6a02</OrderId>        <OrderType>port_in</OrderType>        <BillingTelephoneNumber>9193491234</BillingTelephoneNumber>        <LNPLosingCarrierId>1537</LNPLosingCarrierId>        <LNPLosingCarrierName>Test Losing Carrier L3</LNPLosingCarrierName>        <ProcessingStatus>SUBMITTED</ProcessingStatus>        <RequestedFOCDate>2015-06-03T15:30:00.000Z</RequestedFOCDate>        <VendorId>49</VendorId>        <VendorName>Bandwidth CLEC</VendorName>        <PON>BWC1433343996123</PON>    </lnpPortInfoForGivenStatus>    <lnpPortInfoForGivenStatus>        <CountOfTNs>1</CountOfTNs>        <userId>byo_dev</userId>        <lastModifiedDate>2015-06-03T15:10:13.384Z</lastModifiedDate>        <OrderDate>2015-06-03T15:10:12.808Z</OrderDate>        <OrderId>98939562-90b0-40e9-8335-5526432d9741</OrderId>        <OrderType>port_in</OrderType>        <BillingTelephoneNumber>7576768750</BillingTelephoneNumber>        <LNPLosingCarrierId>1537</LNPLosingCarrierId>        <LNPLosingCarrierName>Test Losing Carrier L3</LNPLosingCarrierName>        <ProcessingStatus>SUBMITTED</ProcessingStatus>        <RequestedFOCDate>2015-06-03T15:30:00.000Z</RequestedFOCDate>        <VendorId>49</VendorId>        <VendorName>Bandwidth CLEC</VendorName>        <PON>BWC1433344213212</PON>    </lnpPortInfoForGivenStatus></LNPResponseWrapper>"),
			new Response(200, [], "<?xml version=\"1.0\" encoding=\"UTF-8\" standalone=\"yes\"?><LnpOrderResponse>    <ProcessingStatus>SUBMITTED</ProcessingStatus>    <RequestedFocDate>2015-06-03T15:30:00Z</RequestedFocDate>    <LoaAuthorizingPerson>Joe Blow</LoaAuthorizingPerson>    <Subscriber>        <SubscriberType>BUSINESS</SubscriberType>        <BusinessName>Company</BusinessName>        <ServiceAddress>            <HouseNumber>123</HouseNumber>            <StreetName>EZ Street</StreetName>            <City>Raleigh</City>            <StateCode>NC</StateCode>            <Zip>27615</Zip>            <County>Wake</County>            <Country>United States</Country>            <AddressType>Service</AddressType>        </ServiceAddress>    </Subscriber>    <BillingTelephoneNumber>9193491234</BillingTelephoneNumber>    <ListOfPhoneNumbers>        <PhoneNumber>9193491234</PhoneNumber>    </ListOfPhoneNumbers>    <PON>BWC1433343996123</PON>    <AccountId>9500249</AccountId>    <SiteId>2297</SiteId>    <PeerId>500655</PeerId>    <LosingCarrierName>Test Losing Carrier L3</LosingCarrierName>    <VendorName>Bandwidth CLEC</VendorName>    <OrderCreateDate>2015-06-03T15:06:35.533Z</OrderCreateDate>    <LastModifiedDate>2015-06-03T15:06:36.234Z</LastModifiedDate>    <userId>System</userId>    <LastModifiedBy>System</LastModifiedBy>    <PartialPort>false</PartialPort>    <Immediately>false</Immediately>    <Triggered>false</Triggered></LnpOrderResponse>"),
			new Response(200, [], "<?xml version=\"1.0\" encoding=\"UTF-8\" standalone=\"yes\"?><OrderHistoryWrapper>    <OrderHistory>        <OrderDate>2015-06-03T15:06:35.765Z</OrderDate>        <Note>LOA required</Note>        <Author>byo_dev</Author>        <Status>PENDING_DOCUMENTS</Status>    </OrderHistory>    <OrderHistory>        <OrderDate>2015-06-03T15:06:36.234Z</OrderDate>        <Note>Order has been created</Note>        <Author>System</Author>        <Status>SUBMITTED</Status>        <Difference>LoaDate : \"\" --&gt; Wed Jun 03 15:06:35 UTC 2015</Difference>    </OrderHistory></OrderHistoryWrapper>"),
			new Response(200, [], "<?xml version=\"1.0\" encoding=\"UTF-8\" standalone=\"yes\"?><Quantity><Count>4</Count></Quantity>"),
        ]);

        self::$container = [];
        $history = Middleware::history(self::$container);
        $handler = HandlerStack::create($mock);
        $handler->push($history);

        $client = new Iris\Client("test", "test", Array('url' => 'https://api.test.inetwork.com/v1.0', 'handler' => $handler));
        $account = new Iris\Account(9500249, $client);
        self::$portins = $account->portins();
    }

	public function testPortinsCreate()
	{
        $portin = self::$portins->create(array(
            "BillingTelephoneNumber" => "6882015002",
            "Subscriber" => array(
                "SubscriberType" => "BUSINESS",
                "BusinessName" => "Acme Corporation",
                "ServiceAddress" => array(
                    "HouseNumber" => "1623",
                    "StreetName" => "Brockton Ave",
                    "City" => "Los Angeles",
                    "StateCode" => "CA",
                    "Zip" => "90025",
                    "Country" => "USA"
                )
            ),
            "LoaAuthorizingPerson" => "John Doe",
            "ListOfPhoneNumbers" => array(
                "PhoneNumber" => array("9882015025", "9882015026")
            ),
            "SiteId" => "365",
            "Triggered" => "false"
        ), false);

		$this->assertEquals("9882015026", $portin->ListOfPhoneNumbers->PhoneNumber[1]);
		$this->assertEquals("Brockton Ave", $portin->Subscriber->ServiceAddress->StreetName);

		$portin = self::$portins->create(array(
            "BillingTelephoneNumber" => "6882015002",
            "Subscriber" => array(
                "SubscriberType" => "BUSINESS",
                "BusinessName" => "Acme Corporation",
                "ServiceAddress" => array(
                    "HouseNumber" => "1623",
                    "StreetName" => "Brockton Ave",
                    "City" => "Los Angeles",
                    "StateCode" => "CA",
                    "Zip" => "90025",
                    "Country" => "USA"
                )
            ),
            "LoaAuthorizingPerson" => "John Doe",
            "ListOfPhoneNumbers" => array(
                "PhoneNumber" => array("9882015025", "9882015026")
            ),
            "SiteId" => "365",
            "Triggered" => "false"
        ));

		$this->assertEquals("d28b36f7-fa96-49eb-9556-a40fca49f7c6", $portin->OrderId);

		$this->assertEquals("POST", self::$container[self::$index]['request']->getMethod());
        $this->assertEquals("https://api.test.inetwork.com/v1.0/accounts/9500249/portins", self::$container[self::$index]['request']->getUri());
        self::$index++;
	}

	public function testPortinsLoasDelete()
	{
		$portin = self::$portins->create(array(
			"OrderId" => "d28b36f7-fa96-49eb-9556-a40fca49f7c6"
		), false);
		$portin->loas_delete('test.txt');
		$this->assertEquals("DELETE", self::$container[self::$index]['request']->getMethod());
		$this->assertEquals("https://api.test.inetwork.com/v1.0/accounts/9500249/portins/d28b36f7-fa96-49eb-9556-a40fca49f7c6/loas/test.txt", self::$container[self::$index]['request']->getUri());
		self::$index++;
	}

	public function testPortinsLoasGetMetadata()
	{
		$portin = self::$portins->create(array(
			"OrderId" => "d28b36f7-fa96-49eb-9556-a40fca49f7c6"
		), false);
		$meta = $portin->get_metadata('test.txt');

		$this->assertEquals("test.txt", $meta->DocumentName);
		$this->assertEquals("LOA", $meta->DocumentType);

		$this->assertEquals("GET", self::$container[self::$index]['request']->getMethod());
		$this->assertEquals("https://api.test.inetwork.com/v1.0/accounts/9500249/portins/d28b36f7-fa96-49eb-9556-a40fca49f7c6/loas/test.txt/metadata", self::$container[self::$index]['request']->getUri());
		self::$index++;
	}

	public function testPortinsLoasSetMetadata()
	{
		$portin = self::$portins->create(array(
			"OrderId" => "d28b36f7-fa96-49eb-9556-a40fca49f7c6"
		), false);

		$meta_new = array(
			"DocumentName" => "text.txt",
			"DocumentType" => "INVOICE"
		);
		$portin->set_metadata('test.txt', $meta_new);

		$this->assertEquals("PUT", self::$container[self::$index]['request']->getMethod());
		$this->assertEquals("https://api.test.inetwork.com/v1.0/accounts/9500249/portins/d28b36f7-fa96-49eb-9556-a40fca49f7c6/loas/test.txt/metadata", self::$container[self::$index]['request']->getUri());
		self::$index++;
	}

	public function testPortinsLoasDeleteMetadata()
	{
		$portin = self::$portins->create(array(
			"OrderId" => "d28b36f7-fa96-49eb-9556-a40fca49f7c6"
		), false);

		$portin->delete_metadata('test.txt');

		$this->assertEquals("DELETE", self::$container[self::$index]['request']->getMethod());
		$this->assertEquals("https://api.test.inetwork.com/v1.0/accounts/9500249/portins/d28b36f7-fa96-49eb-9556-a40fca49f7c6/loas/test.txt/metadata", self::$container[self::$index]['request']->getUri());
		self::$index++;
	}

	public function testPortinsGetLoas()
	{
		$portin = self::$portins->create(array(
			"OrderId" => "d28b36f7-fa96-49eb-9556-a40fca49f7c6"
		), false);

		$portin->list_loas(true);

		$this->assertEquals("GET", self::$container[self::$index]['request']->getMethod());
		$this->assertEquals("https://api.test.inetwork.com/v1.0/accounts/9500249/portins/d28b36f7-fa96-49eb-9556-a40fca49f7c6/loas?metadata=true", self::$container[self::$index]['request']->getUri());
		self::$index++;
	}

	public function testPortinsDelete() {
		$portin = self::$portins->create(array(
			"OrderId" => "d28b36f7-fa96-49eb-9556-a40fca49f7c6"
		), false);

		$portin->delete();

		$this->assertEquals("DELETE", self::$container[self::$index]['request']->getMethod());
		$this->assertEquals("https://api.test.inetwork.com/v1.0/accounts/9500249/portins/d28b36f7-fa96-49eb-9556-a40fca49f7c6", self::$container[self::$index]['request']->getUri());
		self::$index++;
	}

	public function testPortinsGetActivationStatus() {
		$portin = self::$portins->create(array(
			"OrderId" => "d28b36f7-fa96-49eb-9556-a40fca49f7c6"
		), false);

		$status = $portin->get_activation_status();

		$this->assertEquals("6052609021", $status->ActivatedTelephoneNumbersList->TelephoneNumber[0]);
		$this->assertEquals("GET", self::$container[self::$index]['request']->getMethod());
		$this->assertEquals("https://api.test.inetwork.com/v1.0/accounts/9500249/portins/d28b36f7-fa96-49eb-9556-a40fca49f7c6/activationStatus", self::$container[self::$index]['request']->getUri());
		self::$index++;
	}

	public function testPortinsSetActivationStatus() {
		$portin = self::$portins->create(array(
			"OrderId" => "d28b36f7-fa96-49eb-9556-a40fca49f7c6"
		), false);

		$status = $portin->set_activation_status([
			"AutoActivationDate" => "2014-08-30T18:30:00+03:00"
		]);

		$this->assertEquals("6052609021", $status->ActivatedTelephoneNumbersList->TelephoneNumber[0]);
		$this->assertEquals("POST", self::$container[self::$index]['request']->getMethod());
		$this->assertEquals("https://api.test.inetwork.com/v1.0/accounts/9500249/portins/d28b36f7-fa96-49eb-9556-a40fca49f7c6/activationStatus", self::$container[self::$index]['request']->getUri());
		self::$index++;
	}

	public function testPortinsUpdate() {
		$portin = self::$portins->create(array(
			"OrderId" => "d28b36f7-fa96-49eb-9556-a40fca49f7c6",
			"Status" => array(
				"Code" => 0,
				"Description" => "Empty"
			)
		), false);
		$portin->RequestedFocDate = "2012-08-30T00:00:00.000Z";
		$portin->update();

		$this->assertEquals(200, $portin->Status->Code);
		$this->assertEquals("Supp request received. Please use the order id to check the status of your order later.", $portin->Status->Description);
		$this->assertEquals("PUT", self::$container[self::$index]['request']->getMethod());
		$this->assertEquals("https://api.test.inetwork.com/v1.0/accounts/9500249/portins/d28b36f7-fa96-49eb-9556-a40fca49f7c6", self::$container[self::$index]['request']->getUri());
		self::$index++;
	}

	public function testPortinsGet() {
		$portins = self::$portins->getList(["status" => "x" ]);

		$this->assertEquals(2, count($portins));
		$json = '{"CountOfTNs":"1","lastModifiedDate":"2015-06-03T15:06:36.234Z","OrderDate":"2015-06-03T15:06:35.533Z","OrderType":"port_in","LNPLosingCarrierId":"1537","LNPLosingCarrierName":"Test Losing Carrier L3","RequestedFOCDate":"2015-06-03T15:30:00.000Z","VendorId":"49","VendorName":"Bandwidth CLEC","PON":"BWC1433343996123","OrderId":"535ba91e-5363-474e-8c97-c374a4aa6a02","ProcessingStatus":"SUBMITTED","userId":"System","BillingTelephoneNumber":"9193491234"}';
		$this->assertEquals($json, json_encode($portins[0]->to_array()));
		$this->assertEquals("GET", self::$container[self::$index]['request']->getMethod());
		$this->assertEquals("https://api.test.inetwork.com/v1.0/accounts/9500249/portins?status=x&page=1&size=30", self::$container[self::$index]['request']->getUri());
		self::$index++;
	}

	public function testPortinGet()
	{
		$portin = self::$portins->portin("d28b36f7-fa96-49eb-9556-a40fca49f7c6");

		$json = '{"VendorName":"Bandwidth CLEC","PON":"BWC1433343996123","AccountId":"9500249","PeerId":"500655","OrderCreateDate":"2015-06-03T15:06:35.533Z","LastModifiedBy":"System","PartialPort":"false","Immediately":"false","OrderId":"d28b36f7-fa96-49eb-9556-a40fca49f7c6","ProcessingStatus":"SUBMITTED","RequestedFocDate":"2015-06-03T15:30:00Z","LosingCarrierName":"Test Losing Carrier L3","LastModifiedDate":"2015-06-03T15:06:36.234Z","userId":"System","BillingTelephoneNumber":"9193491234","Subscriber":{"SubscriberType":"BUSINESS","BusinessName":"Company","ServiceAddress":{"City":"Raleigh","HouseNumber":"123","StreetName":"EZ Street","StateCode":"NC","Zip":"27615","Country":"United States","County":"Wake","AddressType":"Service"}},"LoaAuthorizingPerson":"Joe Blow","ListOfPhoneNumbers":{"PhoneNumber":"9193491234"},"SiteId":"2297","Triggered":"false"}';
		$this->assertEquals($json, json_encode($portin->to_array()));

		$this->assertEquals("GET", self::$container[self::$index]['request']->getMethod());
		$this->assertEquals("https://api.test.inetwork.com/v1.0/accounts/9500249/portins/d28b36f7-fa96-49eb-9556-a40fca49f7c6", self::$container[self::$index]['request']->getUri());
		self::$index++;
	}

	public function testPortinsHistory() {
		$portin = self::$portins->create(array(
			"OrderId" => "d28b36f7-fa96-49eb-9556-a40fca49f7c6"
		), false);

        $history = $portin->history();

        $this->assertEquals(2, count($history->OrderHistory));

		$json = '{"OrderHistory":[{"OrderDate":"2015-06-03T15:06:35.765Z","Note":"LOA required","Author":"byo_dev","Status":"PENDING_DOCUMENTS"},{"OrderDate":"2015-06-03T15:06:36.234Z","Note":"Order has been created","Author":"System","Status":"SUBMITTED","Difference":"LoaDate : \"\" --> Wed Jun 03 15:06:35 UTC 2015"}]}';
		$this->assertEquals($json, json_encode($history));

        $this->assertEquals("GET", self::$container[self::$index]['request']->getMethod());
        $this->assertEquals("https://api.test.inetwork.com/v1.0/accounts/9500249/portins/d28b36f7-fa96-49eb-9556-a40fca49f7c6/history", self::$container[self::$index]['request']->getUri());
        self::$index++;
    }

	public function testTotals() {
        $numbers = self::$portins->create(array(
			"OrderId" => "d28b36f7-fa96-49eb-9556-a40fca49f7c6"
		), false)->totals([ "status" => "x" ]);

        $this->assertEquals(4, $numbers);
        $this->assertEquals("GET", self::$container[self::$index]['request']->getMethod());
        $this->assertEquals("https://api.test.inetwork.com/v1.0/accounts/9500249/portins/d28b36f7-fa96-49eb-9556-a40fca49f7c6/totals?status=x", self::$container[self::$index]['request']->getUri());
        self::$index++;
    }


}
