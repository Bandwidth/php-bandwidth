<?php

use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Middleware;

class AccountTest extends PHPUnit_Framework_TestCase {
    public static $container;
    public static $account;
    public static $index = 0;

    public static function setUpBeforeClass() {
        $mock = new MockHandler([
            new Response(200, [], "<?xml version=\"1.0\" encoding=\"UTF-8\" standalone=\"yes\"?> <LineOptionOrderResponse><LineOptions> <CompletedNumbers><TelephoneNumber>2013223685</TelephoneNumber> </CompletedNumbers><Errors><Error><TelephoneNumber>5209072452</TelephoneNumber> <ErrorCode>5071</ErrorCode><Description>Telephone number is not available on the system.</Description></Error> <Error><TelephoneNumber>5209072451</TelephoneNumber> <ErrorCode>13518</ErrorCode><Description>CNAM for telephone number is applied at the Location level and it is notapplicable at the TN level.</Description> </Error></Errors> </LineOptions></LineOptionOrderResponse>"),
            new Response(200, [], "<?xml version=\"1.0\"?> <SearchResult><ResultCount>1</ResultCount> <TelephoneNumberDetailList><TelephoneNumberDetail> <City>KNIGHTDALE</City> <LATA>426</LATA> <RateCenter>KNIGHTDALE</RateCenter> <State>NC</State> <FullNumber>9192956932</FullNumber> <Tier>0</Tier><VendorId>49</VendorId> <VendorName>Bandwidth CLEC</VendorName></TelephoneNumberDetail> </TelephoneNumberDetailList></SearchResult>"),
            new Response(200, [], "<?xml version=\"1.0\"?> <SearchResult><ResultCount>2</ResultCount> <TelephoneNumberDetailList><TelephoneNumberDetail> <City>KNIGHTDALE</City> <LATA>426</LATA> <RateCenter>KNIGHTDALE</RateCenter> <State>NC</State> <FullNumber>9192956932</FullNumber> <Tier>0</Tier><VendorId>49</VendorId> <VendorName>Bandwidth CLEC</VendorName></TelephoneNumberDetail><TelephoneNumberDetail> <City>KNIGHTDALE</City> <LATA>426</LATA> <RateCenter>KNIGHTDALE</RateCenter> <State>NC</State> <FullNumber>9192956932</FullNumber> <Tier>0</Tier><VendorId>49</VendorId> <VendorName>Bandwidth CLEC</VendorName></TelephoneNumberDetail> </TelephoneNumberDetailList></SearchResult>"),
            new Response(200, [], "<?xml version=\"1.0\" encoding=\"UTF-8\" standalone=\"no\"?> <SearchResult><ResultCount>5</ResultCount> <TelephoneNumberList><TelephoneNumber>9194390154</TelephoneNumber> <TelephoneNumber>9194390158</TelephoneNumber> <TelephoneNumber>9194390176</TelephoneNumber> <TelephoneNumber>9194390179</TelephoneNumber> <TelephoneNumber>9194390185</TelephoneNumber></TelephoneNumberList> </SearchResult>"),
            new Response(400, [], "<?xml version=\"1.0\" encoding=\"UTF-8\" standalone=\"yes\"?> <SearchResult><Error> <Code>4000</Code> <Description>The area code of telephone numbers can not end with 11. </Description></Error><ResultCount>0</ResultCount> </SearchResult>"),
            new Response(200, [],"<?xml version=\"1.0\" encoding=\"UTF-8\" standalone=\"yes\"?><SearchResult/>"),
            new Response(201, ['Location' => 'https://api.test.inetwork.com:443/v1.0/accounts/9500249/tnsreservation/2489']),
            new Response(200, [], "<?xml version=\"1.0\"?><ReservationResponse><Reservation> <ReservationId>0099ff73-da96-4303-8a0a-00ff316c07aa</ReservationId> <AccountId>14</AccountId> <ReservationExpires>0</ReservationExpires> <ReservedTn>2512027430</ReservedTn></Reservation> </ReservationResponse>"),
            new Response(200, []),
            new Response(200, [], "<?xml version=\"1.0\" encoding=\"UTF-8\"?><NumberPortabilityResponse>   <SupportedRateCenters />   <UnsupportedRateCenters>      <RateCenterGroup>         <RateCenter>BALTIMORE</RateCenter>         <City>BALTIMORE</City>         <State>MD</State>         <LATA>238</LATA>         <TnList>            <Tn>4109255199</Tn>            <Tn>4104685864</Tn>         </TnList>      </RateCenterGroup>      <RateCenterGroup>         <RateCenter>SPARKSGLNC</RateCenter>         <City>SPARKS GLENCOE</City>         <State>MD</State>         <LATA>238</LATA>         <TnList>            <Tn>4103431313</Tn>            <Tn>4103431561</Tn>         </TnList>      </RateCenterGroup>   </UnsupportedRateCenters>   <PartnerSupportedRateCenters>      <!-- Only available for fullCheck=offnetportability -->      <RateCenterGroup>         <RateCenter>FT COLLINS</RateCenter>         <City>FORT COLLINS</City>         <State>CO</State>         <LATA>656</LATA>         <Tiers>            <Tier>1</Tier>         </Tiers>         <TnList>            <Tn>4109235436</Tn>         </TnList>      </RateCenterGroup>   </PartnerSupportedRateCenters>   <SupportedLosingCarriers>      <LosingCarrierTnList>         <LosingCarrierSPID>9998</LosingCarrierSPID>         <LosingCarrierName>Test Losing Carrier L3</LosingCarrierName>         <LosingCarrierIsWireless>false</LosingCarrierIsWireless>         <LosingCarrierAccountNumberRequired>false</LosingCarrierAccountNumberRequired>         <LosingCarrierMinimumPortingInterval>5</LosingCarrierMinimumPortingInterval>         <TnList>            <Tn>4109255199</Tn>            <Tn>4104685864</Tn>            <Tn>4103431313</Tn>            <Tn>4103431561</Tn>         </TnList>      </LosingCarrierTnList>   </SupportedLosingCarriers>   <UnsupportedLosingCarriers /></NumberPortabilityResponse>"),
            new Response(200, [], "<?xml version=\"1.0\" encoding=\"UTF-8\" standalone=\"yes\"?><SearchResultForAvailableNpaNxx>    <AvailableNpaNxxList>        <AvailableNpaNxx>            <City>COMPTON:COMPTON DA</City>            <Npa>424</Npa>            <Nxx>242</Nxx>            <Quantity>7</Quantity>            <State>CA</State>        </AvailableNpaNxx>        <AvailableNpaNxx>            <City>COMPTON:GARDENA DA</City>            <Npa>424</Npa>            <Nxx>246</Nxx>            <Quantity>5</Quantity>            <State>CA</State>        </AvailableNpaNxx>    </AvailableNpaNxxList></SearchResultForAvailableNpaNxx>"),
            new Response(200, [], "<?xml version=\"1.0\" encoding=\"UTF-8\" standalone=\"yes\"?><TNs>    <TotalCount>4</TotalCount>    <Links>        <first>Link=&lt;https://api.test.inetwork.com:443/v1.0/accounts/9500249/inserviceNumbers?size=500&amp;page=1&gt;;rel=\"first\";</first>    </Links>    <TelephoneNumbers>        <Count>4</Count>        <TelephoneNumber>8183386247</TelephoneNumber>        <TelephoneNumber>8183386249</TelephoneNumber>        <TelephoneNumber>8183386251</TelephoneNumber>        <TelephoneNumber>8183386252</TelephoneNumber>    </TelephoneNumbers></TNs>"),
            new Response(200, [], "<?xml version=\"1.0\" encoding=\"UTF-8\" standalone=\"yes\"?><Quantity><Count>4</Count></Quantity>"),
            new Response(200, [], "<?xml version=\"1.0\"?><TNs><TotalCount>4</TotalCount><Links><first></first></Links><TelephoneNumbers><Count>2</Count><TelephoneNumber>4158714245</TelephoneNumber><TelephoneNumber>4352154439</TelephoneNumber></TelephoneNumbers></TNs>"),
            new Response(200, [], "<?xml version=\"1.0\" encoding=\"UTF-8\" standalone=\"yes\"?><Quantity><Count>4</Count></Quantity>"),
            new Response(200, [], "<?xml version=\"1.0\" encoding=\"UTF-8\" standalone=\"yes\"?><BdrCreationResponse><Info>Your BDR archive is currently being constructed</Info> </BdrCreationResponse>")
        ]);

        self::$container = [];
        $history = Middleware::history(self::$container);
        $handler = HandlerStack::create($mock);
        $handler->push($history);

        $client = new Iris\Client("test", "test", Array('url' => 'https://api.test.inetwork.com/v1.0', 'handler' => $handler));
        self::$account = new Iris\Account(9500249, $client);
    }

    public function testLineOption() {
		$TnLineOptions = new \Iris\TnLineOptions(array(
			"TnLineOptions" => [
				[ "TelephoneNumber" => "5209072451", "CallingNameDisplay" => "off" ],
				[ "TelephoneNumber" => "5209072452", "CallingNameDisplay" => "on" ],
				[ "TelephoneNumber" => "5209072453", "CallingNameDisplay" => "off" ]
			]
		));

        $response = self::$account->lineOptionOrders($TnLineOptions);

        $this->assertEquals("POST", self::$container[self::$index]['request']->getMethod());
        $this->assertEquals("https://api.test.inetwork.com/v1.0/accounts/9500249/lineOptionOrders", (string) self::$container[self::$index]['request']->getUri());
        self::$index++;
    }

    public function testAvailableNumbersSingle() {
        $response = self::$account->availableNumbers();

        $this->assertEquals("KNIGHTDALE", $response[0]->City);
        $this->assertEquals("GET", self::$container[self::$index]['request']->getMethod());
        $this->assertEquals("https://api.test.inetwork.com/v1.0/accounts/9500249/availableNumbers", (string) self::$container[self::$index]['request']->getUri());
        self::$index++;
    }

    public function testAvailableNumbers() {
        $response = self::$account->availableNumbers();

        $this->assertEquals("KNIGHTDALE", $response[1]->City);
        $this->assertEquals("GET", self::$container[self::$index]['request']->getMethod());
        $this->assertEquals("https://api.test.inetwork.com/v1.0/accounts/9500249/availableNumbers", (string) self::$container[self::$index]['request']->getUri());
        self::$index++;
    }

    public function testAvailableNumbers2() {
        $response = self::$account->availableNumbers();

        $this->assertEquals("9194390154", $response[0]->TelephoneNumber[0]);
        $this->assertEquals("GET", self::$container[self::$index]['request']->getMethod());
        $this->assertEquals("https://api.test.inetwork.com/v1.0/accounts/9500249/availableNumbers", (string) self::$container[self::$index]['request']->getUri());
        self::$index++;
    }

    /**
     * @expectedException \Iris\ResponseException
     * @expectedExceptionCode 4000
     */
    public function testAvailableNumbersError() {
        $response = self::$account->availableNumbers();

        $this->assertEquals("GET", self::$container[self::$index]['request']->getMethod());
        $this->assertEquals("https://api.test.inetwork.com/v1.0/accounts/9500249/availableNumbers", (string) self::$container[self::$index]['request']->getUri());
        self::$index++;
    }

    public function testAvailableNumbersNoResults() {
        $response = self::$account->availableNumbers();

        $this->assertEquals("GET", self::$container[self::$index]['request']->getMethod());
        $this->assertEquals("https://api.test.inetwork.com/v1.0/accounts/9500249/availableNumbers", (string) self::$container[self::$index]['request']->getUri());
        self::$index++;
    }

    public function testTnReservation() {
        $resertation = self::$account->tnsreservations()->create(["ReservedTn" => "2512027430"]);

        self::$index++;
        $json = '{"ReservedTn":"2512027430","ReservationId":"2489"}';
		$this->assertEquals($json, json_encode($resertation->to_array()));
        $this->assertEquals("2489", $resertation->get_id());
        $this->assertEquals("POST", self::$container[self::$index]['request']->getMethod());
        $this->assertEquals("https://api.test.inetwork.com/v1.0/accounts/9500249/tnreservation", (string) self::$container[self::$index]['request']->getUri());
        self::$index++;
    }

    public function testGetTnReservation() {
        $resertation = self::$account->tnsreservations()->tnsreservation("0099ff73-da96-4303-8a0a-00ff316c07aa");

        $json = '{"ReservedTn":"2512027430","ReservationId":"0099ff73-da96-4303-8a0a-00ff316c07aa","ReservationExpires":"0","AccountId":"14"}';
		$this->assertEquals($json, json_encode($resertation->to_array()));
        $this->assertEquals("0099ff73-da96-4303-8a0a-00ff316c07aa", $resertation->get_id());
        $this->assertEquals("GET", self::$container[self::$index]['request']->getMethod());
        $this->assertEquals("https://api.test.inetwork.com/v1.0/accounts/9500249/tnreservation/0099ff73-da96-4303-8a0a-00ff316c07aa", (string) self::$container[self::$index]['request']->getUri());
        self::$index++;
    }
    public function testDeleteTnReservation() {
        $resertation = self::$account->tnsreservations()->create(["ReservationId" => "0099ff73-da96-4303-8a0a-00ff316c07aa"], false);
        $resertation->delete();

        $this->assertEquals("DELETE", self::$container[self::$index]['request']->getMethod());
        $this->assertEquals("https://api.test.inetwork.com/v1.0/accounts/9500249/tnreservation/0099ff73-da96-4303-8a0a-00ff316c07aa", (string) self::$container[self::$index]['request']->getUri());
        self::$index++;
    }

    public function testlnpChecker() {
        $res = self::$account->lnpChecker(["4109255199", "9196190594"], "true");

        $json = '{"SupportedRateCenters":"","UnsupportedRateCenters":{"RateCenterGroup":[{"RateCenter":"BALTIMORE","City":"BALTIMORE","State":"MD","LATA":"238","TnList":{"Tn":["4109255199","4104685864"]}},{"RateCenter":"SPARKSGLNC","City":"SPARKS GLENCOE","State":"MD","LATA":"238","TnList":{"Tn":["4103431313","4103431561"]}}]},"PartnerSupportedRateCenters":{"RateCenterGroup":{"RateCenter":"FT COLLINS","City":"FORT COLLINS","State":"CO","LATA":"656","TnList":{"Tn":"4109235436"},"Tiers":{"Tier":"1"}}},"SupportedLosingCarriers":{"LosingCarrierTnList":{"LosingCarrierSPID":"9998","LosingCarrierName":"Test Losing Carrier L3","LosingCarrierIsWireless":"false","LosingCarrierAccountNumberRequired":"false","LosingCarrierMinimumPortingInterval":"5","TnList":{"Tn":["4109255199","4104685864","4103431313","4103431561"]}}}}';

		$this->assertEquals($json, json_encode($res->to_array()));

        $this->assertEquals("POST", self::$container[self::$index]['request']->getMethod());
        $this->assertEquals("https://api.test.inetwork.com/v1.0/accounts/9500249/lnpchecker?fullCheck=true", (string) self::$container[self::$index]['request']->getUri());
        self::$index++;
    }
    public function testNpaGet() {
        $npas = self::$account->availableNpaNxx(["state" => "CA"]);

        $json = '{"City":"COMPTON:COMPTON DA","Npa":"424","Nxx":"242","Quantity":"7","State":"CA"}';
        $this->assertEquals($json, json_encode($npas[0]->to_array()));
        $this->assertEquals("GET", self::$container[self::$index]['request']->getMethod());
        $this->assertEquals("https://api.test.inetwork.com/v1.0/accounts/9500249/availableNpaNxx?state=CA", (string) self::$container[self::$index]['request']->getUri());
        self::$index++;
    }
    public function testInserviceGet() {
        $numbers = self::$account->inserviceNumbers(["page"=> "2", "type" => "x"]);

        $json = '{"TelephoneNumber":["8183386247","8183386249","8183386251","8183386252"]}';
        $this->assertEquals($json, json_encode($numbers->to_array()));
        $this->assertEquals("GET", self::$container[self::$index]['request']->getMethod());
        $this->assertEquals("https://api.test.inetwork.com/v1.0/accounts/9500249/inserviceNumbers?page=2&type=x", (string) self::$container[self::$index]['request']->getUri());
        self::$index++;
    }

    public function testInserviceTotals() {
        $numbers = self::$account->inserviceNumbers_totals();

        $this->assertEquals(4, $numbers);
        $this->assertEquals("GET", self::$container[self::$index]['request']->getMethod());
        $this->assertEquals("https://api.test.inetwork.com/v1.0/accounts/9500249/inserviceNumbers/totals", (string) self::$container[self::$index]['request']->getUri());
        self::$index++;
    }

    public function testDiscNumbersGet() {
        $numbers = self::$account->disnumbers(["page"=> "2", "type" => "x"]);

        $json = '{"TelephoneNumber":["4158714245","4352154439"]}';
        $this->assertEquals($json, json_encode($numbers->to_array()));
        $this->assertEquals("GET", self::$container[self::$index]['request']->getMethod());
        $this->assertEquals("https://api.test.inetwork.com/v1.0/accounts/9500249/discNumbers?page=2&type=x", (string) self::$container[self::$index]['request']->getUri());
        self::$index++;
    }

    public function testDisknumbersTotals() {
        $numbers = self::$account->disnumbers_totals();

        $this->assertEquals(4, $numbers);
        $this->assertEquals("GET", self::$container[self::$index]['request']->getMethod());
        $this->assertEquals("https://api.test.inetwork.com/v1.0/accounts/9500249/discNumbers/totals", (string) self::$container[self::$index]['request']->getUri());
        self::$index++;
    }

    public function testBdr() {
        $response = self::$account->bdrs(new \Iris\Bdr([
            "StartDate" => "xx-yy-zzzz",
            "EndDate" => "xx-yy-zzzz",
        ]));

        $this->assertEquals("Your BDR archive is currently being constructed", $response->Info);
        $this->assertEquals("POST", self::$container[self::$index]['request']->getMethod());
        $this->assertEquals("https://api.test.inetwork.com/v1.0/accounts/9500249/bdrs", (string) self::$container[self::$index]['request']->getUri());
        self::$index++;
    }

}
