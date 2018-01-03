<?php
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Middleware;

class OtherTest extends PHPUnit_Framework_TestCase {
    public static $container;
    public static $client;
    public static $index = 0;

    public static function setUpBeforeClass() {
        $mock = new MockHandler([
            new Response(200, [], "<?xml version=\"1.0\" encoding=\"UTF-8\" standalone=\"yes\"?><CityResponse>    <ResultCount>618</ResultCount>    <Cities>        <City>            <RcAbbreviation>PINEHURST</RcAbbreviation>            <Name>ABERDEEN</Name>        </City>        <City>            <RcAbbreviation>JULIAN</RcAbbreviation>            <Name>ADVANCE</Name>        </City>    </Cities></CityResponse>"),
            new Response(200, [], "<?xml version=\"1.0\" encoding=\"UTF-8\" standalone=\"yes\"?><RateCenters>    <ResultCount>652</ResultCount>    <RateCenters>        <RateCenter>            <Abbreviation>AGOURA</Abbreviation>            <Name>AGOURA</Name>        </RateCenter>        <RateCenter>            <Abbreviation>ALAMITOS</Abbreviation>            <Name>ALAMITOS</Name>        </RateCenter>    </RateCenters></RateCenters>"),
        ]);

        self::$container = [];
        $history = Middleware::history(self::$container);
        $handler = HandlerStack::create($mock);
        $handler->push($history);

        self::$client = new Iris\Client("test", "test", Array('url' => 'https://api.test.inetwork.com/v1.0', 'handler' => $handler));
    }

    public function testCitiesGet() {
        $c = new \Iris\Cities(self::$client);
        $cities = $c->getList(["state" => "NC"]);

        $json = '{"RcAbbreviation":"PINEHURST","Name":"ABERDEEN"}';
        $this->assertEquals($json, json_encode($cities[0]->to_array()));
        $this->assertEquals("GET", self::$container[self::$index]['request']->getMethod());
        $this->assertEquals("https://api.test.inetwork.com/v1.0/cities?state=NC", self::$container[self::$index]['request']->getUri());
        self::$index++;
    }

    public function testRC() {
        $c = new \Iris\RateCenter(self::$client);
        $cities = $c->getList(["state" => "CA"]);

        $json = '{"Name":"AGOURA","Abbreviation":"AGOURA"}';
        $this->assertEquals($json, json_encode($cities[0]->to_array()));
        $this->assertEquals("GET", self::$container[self::$index]['request']->getMethod());
        $this->assertEquals("https://api.test.inetwork.com/v1.0/rateCenters?state=CA", self::$container[self::$index]['request']->getUri());
        self::$index++;
    }

}
