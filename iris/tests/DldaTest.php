<?php
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Middleware;

class DldasTest extends PHPUnit_Framework_TestCase {
    public static $container;
    public static $dldas;
    public static $index = 0;

    public static function setUpBeforeClass() {
        $mock = new MockHandler([
            new Response(200, [], "<?xml version=\"1.0\" encoding=\"UTF-8\" standalone=\"yes\" ?><DldaOrderResponse><DldaOrder><CustomerOrderId>5a88d16d-f8a9-45c5-a5db-137d700c6a22</CustomerOrderId><OrderCreateDate>2014-07-10T12:38:11.833Z</OrderCreateDate><AccountId>14</AccountId><CreatedByUser>jbm</CreatedByUser><OrderId>ea9e90c2-77a4-4f82-ac47-e1c5bb1311f4</OrderId><LastModifiedDate>2014-07-10T12:38:11.833Z</LastModifiedDate><ProcessingStatus>RECEIVED</ProcessingStatus><DldaTnGroups><DldaTnGroup><TelephoneNumbers><TelephoneNumber>2053778335</TelephoneNumber><TelephoneNumber>2053865784</TelephoneNumber></TelephoneNumbers><AccountType>BUSINESS</AccountType><ListingType>LISTED</ListingType><ListingName><FirstName>Joe</FirstName><LastName>Smith</LastName></ListingName><ListAddress>true</ListAddress><Address><HouseNumber>12</HouseNumber><StreetName>ELM</StreetName><City>New York</City><StateCode>NY</StateCode><Zip>10007</Zip><Country>United States</Country><AddressType>Dlda</AddressType></Address></DldaTnGroup></DldaTnGroups></DldaOrder></DldaOrderResponse>"),
            new Response(200, [], "<?xml version=\"1.0\" encoding=\"UTF-8\" standalone=\"yes\" ?><ResponseSelectWrapper><ListOrderIdUserIdDate><TotalCount>3</TotalCount><OrderIdUserIdDate><accountId>14</accountId><CountOfTNs>2</CountOfTNs><userId>team_ua</userId><lastModifiedDate>2014-07-07T10:06:43.427Z</lastModifiedDate><OrderType>dlda</OrderType><OrderDate>2014-07-07T10:06:43.427Z</OrderDate><orderId>37a6447c-1a0b-4be9-ba89-3f5cb0aea142</orderId><OrderStatus>FAILED</OrderStatus></OrderIdUserIdDate><OrderIdUserIdDate><accountId>14</accountId><CountOfTNs>2</CountOfTNs><userId>team_ua</userId><lastModifiedDate>2014-07-07T10:05:56.595Z</lastModifiedDate><OrderType>dlda</OrderType><OrderDate>2014-07-07T10:05:56.595Z</OrderDate><orderId>743b0e64-3350-42e4-baa6-406dac7f4a85</orderId><OrderStatus>RECEIVED</OrderStatus></OrderIdUserIdDate><OrderIdUserIdDate><accountId>14</accountId><CountOfTNs>2</CountOfTNs><userId>team_ua</userId><lastModifiedDate>2014-07-07T09:32:17.234Z</lastModifiedDate><OrderType>dlda</OrderType><OrderDate>2014-07-07T09:32:17.234Z</OrderDate><orderId>f71eb4d2-bfef-4384-957f-45cd6321185e</orderId><OrderStatus>RECEIVED</OrderStatus></OrderIdUserIdDate></ListOrderIdUserIdDate></ResponseSelectWrapper>"),
            new Response(200, [], "<?xml version=\"1.0\" encoding=\"UTF-8\" standalone=\"yes\" ?><DldaOrderResponse><DldaOrder><CustomerOrderId>5a88d16d-f8a9-45c5-a5db-137d700c6a22</CustomerOrderId><OrderCreateDate>2014-07-10T12:38:11.833Z</OrderCreateDate><AccountId>14</AccountId><CreatedByUser>jbm</CreatedByUser><OrderId>ea9e90c2-77a4-4f82-ac47-e1c5bb1311f4</OrderId><LastModifiedDate>2014-07-10T12:38:11.833Z</LastModifiedDate><ProcessingStatus>RECEIVED</ProcessingStatus><DldaTnGroups><DldaTnGroup><TelephoneNumbers><TelephoneNumber>2053778335</TelephoneNumber><TelephoneNumber>2053865784</TelephoneNumber></TelephoneNumbers><AccountType>BUSINESS</AccountType><ListingType>LISTED</ListingType><ListingName><FirstName>Joe</FirstName><LastName>Smith</LastName></ListingName><ListAddress>true</ListAddress><Address><HouseNumber>12</HouseNumber><StreetName>ELM</StreetName><City>New York</City><StateCode>NY</StateCode><Zip>10007</Zip><Country>United States</Country><AddressType>Dlda</AddressType></Address></DldaTnGroup></DldaTnGroups></DldaOrder></DldaOrderResponse>"),
            new Response(200),
            new Response(200, [], "<?xml version=\"1.0\"?> <OrderHistoryWrapper><OrderHistory> <OrderDate>2014-09-04T16:28:11.320Z</OrderDate> <Note>The DL/DA request has been received</Note> <Author>jbm</Author><Status>RECEIVED</Status></OrderHistory> <OrderHistory><OrderDate>2014-09-04T16:28:18.742Z</OrderDate> <Note>The DL/DA request is being processed by our 3rd party supplier </Note><Author>jbm</Author><Status>PROCESSING</Status> </OrderHistory><OrderHistory><OrderDate>2014-09-05T19:00:17.968Z</OrderDate> <Note>The DL/DA request is complete for all TNs</Note> <Author>jbm</Author><Status>COMPLETE</Status></OrderHistory> </OrderHistoryWrapper>"),
        ]);

        self::$container = [];
        $history = Middleware::history(self::$container);
        $handler = HandlerStack::create($mock);
        $handler->push($history);

        $client = new Iris\Client("test", "test", Array('url' => 'https://api.test.inetwork.com/v1.0', 'handler' => $handler));
        $account = new Iris\Account(9500249, $client);
        self::$dldas = $account->dldas();
    }

    public function testLidbsPost() {
        $order_data = [
            "CustomerOrderId" => "123",
            "DldaTnGroups" => [
                "DldaTnGroup" => [
                    [
                        "TelephoneNumbers" => [
                            "TelephoneNumber" => "4352154856"
                        ],
                        "AccountType" => "RESIDENTIAL",
                        "ListingType" => "LISTED",
                        "ListAddress" => "true",
                        "ListingName" => [
                            "FirstName" => "FirstName",
                            "FirstName2" => "FirstName2",
                            "LastName" => "LastName",
                            "Designation" => "Designation",
                            "TitleOfLineage" => "TitleOfLineage",
                            "TitleOfAddress" => "TitleOfAddress",
                            "TitleOfAddress2" => "TitleOfAddress2",
                            "TitleOfLineageName2" => "TitleOfLineageName2",
                            "TitleOfAddressName2" => "TitleOfAddressName2",
                            "TitleOfAddress2Name2" => "TitleOfAddress2Name2",
                            "PlaceListingAs" => "PlaceListingAs",
                        ],
                        "Address" => [
                            "HousePrefix" => "HousePrefix",
                            "HouseNumber" => "915",
                            "HouseSuffix" => "HouseSuffix",
                            "PreDirectional" => "PreDirectional",
                            "StreetName" => "StreetName",
                            "StreetSuffix" => "StreetSuffix",
                            "PostDirectional" => "PostDirectional",
                            "AddressLine2" => "AddressLine2",
                            "City" => "City",
                            "StateCode" => "StateCode",
                            "Zip" => "Zip",
                            "PlusFour" => "PlusFour",
                            "Country" => "Country",
                            "AddressType" => "AddressType"
                        ]
                    ]
                ]
            ]
        ];

		$dlda = self::$dldas->create($order_data, false);
        $json = '{"CustomerOrderId":"123","DldaTnGroups":{"DldaTnGroup":[{"TelephoneNumbers":{"TelephoneNumber":"4352154856"},"AccountType":"RESIDENTIAL","ListingType":"LISTED","ListAddress":"true","ListingName":{"FirstName":"FirstName","FirstName2":"FirstName2","LastName":"LastName","Designation":"Designation","TitleOfLineage":"TitleOfLineage","TitleOfAddress":"TitleOfAddress","TitleOfAddress2":"TitleOfAddress2","TitleOfLineageName2":"TitleOfLineageName2","TitleOfAddressName2":"TitleOfAddressName2","TitleOfAddress2Name2":"TitleOfAddress2Name2","PlaceListingAs":"PlaceListingAs"},"Address":{"City":"City","HouseNumber":"915","StreetName":"StreetName","StateCode":"StateCode","Zip":"Zip","Country":"Country","HousePrefix":"HousePrefix","HouseSuffix":"HouseSuffix","PreDirectional":"PreDirectional","StreetSuffix":"StreetSuffix","PostDirectional":"PostDirectional","AddressLine2":"AddressLine2","PlusFour":"PlusFour","AddressType":"AddressType"}}]}}';
        $this->assertEquals($json, json_encode($dlda->to_array()));

        $dlda = self::$dldas->create($order_data);

        $json = '{"CustomerOrderId":"5a88d16d-f8a9-45c5-a5db-137d700c6a22","OrderCreateDate":"2014-07-10T12:38:11.833Z","AccountId":"14","CreatedByUser":"jbm","OrderId":"ea9e90c2-77a4-4f82-ac47-e1c5bb1311f4","LastModifiedDate":"2014-07-10T12:38:11.833Z","ProcessingStatus":"RECEIVED","DldaTnGroups":{"DldaTnGroup":{"TelephoneNumbers":{"TelephoneNumber":["2053778335","2053865784"]},"AccountType":"BUSINESS","ListingType":"LISTED","ListAddress":"true","ListingName":{"FirstName":"Joe","LastName":"Smith"},"Address":{"City":"New York","HouseNumber":"12","StreetName":"ELM","StateCode":"NY","Zip":"10007","Country":"United States","AddressType":"Dlda"}}}}';
        $this->assertEquals($json, json_encode($dlda->to_array()));

        $this->assertEquals("POST", self::$container[self::$index]['request']->getMethod());
        $this->assertEquals("https://api.test.inetwork.com/v1.0/accounts/9500249/dldas", self::$container[self::$index]['request']->getUri());
        self::$index++;
    }

    public function testDldasGet() {
		$dldas = self::$dldas->getList();

        $json = '{"accountId":"14","CountOfTNs":"2","userId":"team_ua","lastModifiedDate":"2014-07-07T10:06:43.427Z","OrderType":"dlda","OrderDate":"2014-07-07T10:06:43.427Z","orderId":"37a6447c-1a0b-4be9-ba89-3f5cb0aea142","OrderStatus":"FAILED"}';
        $this->assertEquals($json, json_encode($dldas[0]->to_array()));

        $this->assertEquals("37a6447c-1a0b-4be9-ba89-3f5cb0aea142", $dldas[0]->get_id());
        $this->assertEquals(3, count($dldas));
        $this->assertEquals("GET", self::$container[self::$index]['request']->getMethod());
        $this->assertEquals("https://api.test.inetwork.com/v1.0/accounts/9500249/dldas", self::$container[self::$index]['request']->getUri());
        self::$index++;
    }

    public function testLidbGet() {
		$dldas = self::$dldas->dlda("7802373f-4f52-4387-bdd1-c5b74833d6e2");

        $json = '{"CustomerOrderId":"5a88d16d-f8a9-45c5-a5db-137d700c6a22","OrderCreateDate":"2014-07-10T12:38:11.833Z","AccountId":"14","CreatedByUser":"jbm","OrderId":"ea9e90c2-77a4-4f82-ac47-e1c5bb1311f4","LastModifiedDate":"2014-07-10T12:38:11.833Z","ProcessingStatus":"RECEIVED","DldaTnGroups":{"DldaTnGroup":{"TelephoneNumbers":{"TelephoneNumber":["2053778335","2053865784"]},"AccountType":"BUSINESS","ListingType":"LISTED","ListAddress":"true","ListingName":{"FirstName":"Joe","LastName":"Smith"},"Address":{"City":"New York","HouseNumber":"12","StreetName":"ELM","StateCode":"NY","Zip":"10007","Country":"United States","AddressType":"Dlda"}}}}';
        $this->assertEquals($json, json_encode($dldas->to_array()));

        $this->assertEquals("GET", self::$container[self::$index]['request']->getMethod());
        $this->assertEquals("https://api.test.inetwork.com/v1.0/accounts/9500249/dldas/7802373f-4f52-4387-bdd1-c5b74833d6e2", self::$container[self::$index]['request']->getUri());
        self::$index++;
    }

    public function testPut() {
        $order_data = [
            "OrderId" => "7802373f-4f52-4387-bdd1-c5b74833d6e2",
            "CustomerOrderId" => "123",
            "DldaTnGroups" => [
                "DldaTnGroup" => [
                    [
                        "TelephoneNumbers" => [
                            "TelephoneNumber" => "4352154856"
                        ],
                        "AccountType" => "RESIDENTIAL",
                        "ListingType" => "LISTED",
                        "ListAddress" => "true",
                        "ListingName" => [
                            "FirstName" => "FirstName",
                            "FirstName2" => "FirstName2",
                            "LastName" => "LastName",
                            "Designation" => "Designation",
                            "TitleOfLineage" => "TitleOfLineage",
                            "TitleOfAddress" => "TitleOfAddress",
                            "TitleOfAddress2" => "TitleOfAddress2",
                            "TitleOfLineageName2" => "TitleOfLineageName2",
                            "TitleOfAddressName2" => "TitleOfAddressName2",
                            "TitleOfAddress2Name2" => "TitleOfAddress2Name2",
                            "PlaceListingAs" => "PlaceListingAs",
                        ],
                        "Address" => [
                            "HousePrefix" => "HousePrefix",
                            "HouseNumber" => "915",
                            "HouseSuffix" => "HouseSuffix",
                            "PreDirectional" => "PreDirectional",
                            "StreetName" => "StreetName",
                            "StreetSuffix" => "StreetSuffix",
                            "PostDirectional" => "PostDirectional",
                            "AddressLine2" => "AddressLine2",
                            "City" => "City",
                            "StateCode" => "StateCode",
                            "Zip" => "Zip",
                            "PlusFour" => "PlusFour",
                            "Country" => "Country",
                            "AddressType" => "AddressType"
                        ]
                    ]
                ]
            ]
        ];

		$dlda = self::$dldas->create($order_data, false);

        $json = '{"CustomerOrderId":"123","OrderId":"7802373f-4f52-4387-bdd1-c5b74833d6e2","DldaTnGroups":{"DldaTnGroup":[{"TelephoneNumbers":{"TelephoneNumber":"4352154856"},"AccountType":"RESIDENTIAL","ListingType":"LISTED","ListAddress":"true","ListingName":{"FirstName":"FirstName","FirstName2":"FirstName2","LastName":"LastName","Designation":"Designation","TitleOfLineage":"TitleOfLineage","TitleOfAddress":"TitleOfAddress","TitleOfAddress2":"TitleOfAddress2","TitleOfLineageName2":"TitleOfLineageName2","TitleOfAddressName2":"TitleOfAddressName2","TitleOfAddress2Name2":"TitleOfAddress2Name2","PlaceListingAs":"PlaceListingAs"},"Address":{"City":"City","HouseNumber":"915","StreetName":"StreetName","StateCode":"StateCode","Zip":"Zip","Country":"Country","HousePrefix":"HousePrefix","HouseSuffix":"HouseSuffix","PreDirectional":"PreDirectional","StreetSuffix":"StreetSuffix","PostDirectional":"PostDirectional","AddressLine2":"AddressLine2","PlusFour":"PlusFour","AddressType":"AddressType"}}]}}';
        $this->assertEquals($json, json_encode($dlda->to_array()));

        $dlda->update();

        $this->assertEquals("PUT", self::$container[self::$index]['request']->getMethod());
        $this->assertEquals("https://api.test.inetwork.com/v1.0/accounts/9500249/dldas/7802373f-4f52-4387-bdd1-c5b74833d6e2", self::$container[self::$index]['request']->getUri());
        self::$index++;


    }

    public function testLidbHistory() {
		$dlda = self::$dldas->create(["OrderId" => "7802373f-4f52-4387-bdd1-c5b74833d6e2"], false);

        $history = $dlda->history();

        $this->assertEquals(3, count($history->OrderHistory));
        $this->assertEquals("COMPLETE", $history->OrderHistory[2]->Status);

        $this->assertEquals("GET", self::$container[self::$index]['request']->getMethod());
        $this->assertEquals("https://api.test.inetwork.com/v1.0/accounts/9500249/dldas/7802373f-4f52-4387-bdd1-c5b74833d6e2/history", self::$container[self::$index]['request']->getUri());
        self::$index++;
    }

}
