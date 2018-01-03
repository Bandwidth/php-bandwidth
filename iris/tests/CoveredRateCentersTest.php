<?php
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Middleware;

class CoveredRateCenterTest extends PHPUnit_Framework_TestCase {
    public static $container;
    public static $rcs;
    public static $index = 0;

    public static function setUpBeforeClass() {
        $mock = new MockHandler([
			new Response(200, [], "<?xml version=\"1.0\" encoding=\"UTF-8\" standalone=\"yes\"?> <CoveredRateCenters><TotalCount>18</TotalCount> <Links><first>Link=&lt;https://api.inetwork.com:443/v1.0/coveredRateCenters?npa=310&amp;size=10&amp;e mbed=Cities&amp;embed=ZipCodes&amp;embed=NpaNxxX&amp;page=1&gt;;rel=\"first\";</first><next>Link=&lt;https://api.inetwork.com:443/v1.0/coveredRateCenters?npa=310&amp;size=10&amp;e mbed=Cities&amp;embed=ZipCodes&amp;embed=NpaNxxX&amp; page=5&gt;;rel=\"next\";</next></Links> <CoveredRateCenter><Name>AVALON</Name><Abbreviation>AVALON</Abbreviation> <State>CA</State><Lata>730</Lata> <AvailableNumberCount>1</AvailableNumberCount> <ZipCodes><ZipCode>90731</ZipCode> </ZipCodes><Cities><City>SAN PEDRO</City> </Cities><Tiers> <Tier>0</Tier></Tiers> <NpaNxxXs><NpaNxxX>3105100</NpaNxxX> <NpaNxxX>3105101</NpaNxxX> <NpaNxxX>3109498</NpaNxxX> <NpaNxxX>3109499</NpaNxxX> <NpaNxxX>4242260</NpaNxxX></NpaNxxXs><Id>1</Id> </CoveredRateCenter> <CoveredRateCenter><Name>BEVERLY HILLS</Name> <Abbreviation>BEVERLYHLS</Abbreviation> <State>CA</State><Lata>730</Lata><AvailableNumberCount>25</AvailableNumberCount> <ZipCodes><ZipCode>90013</ZipCode> <ZipCode>90014</ZipCode> <ZipCode>90015</ZipCode><ZipCode>91504</ZipCode><ZipCode>91505</ZipCode> </ZipCodes><Cities><City>BEVERLY HILLS</City> <City>BURBANK</City> <City>GARDENA</City> <City>LOS ANGELES</City> <City>SHERMAN OAKS</City> <City>SUN VALLEY</City> <City>VAN NUYS</City></Cities> <Tiers><Tier>0</Tier> </Tiers><NpaNxxXs> <NpaNxxX>3102010</NpaNxxX><NpaNxxX>3102011</NpaNxxX><NpaNxxX>3102012</NpaNxxX><NpaNxxX>4247777</NpaNxxX> <NpaNxxX>4247778</NpaNxxX> <NpaNxxX>4247779</NpaNxxX></NpaNxxXs><Id>3</Id> </CoveredRateCenter></CoveredRateCenters>"),
            new Response(200, [], "<?xml version=\"1.0\" encoding=\"UTF-8\" standalone=\"yes\"?> <CoveredRateCenters><CoveredRateCenter><Name>AVALON</Name><Abbreviation>AVALON</Abbreviation> <State>CA</State><Lata>730</Lata> <AvailableNumberCount>1</AvailableNumberCount> <ZipCodes><ZipCode>90731</ZipCode> </ZipCodes><Cities><City>SAN PEDRO</City> </Cities><Tiers> <Tier>0</Tier></Tiers> <NpaNxxXs><NpaNxxX>3105100</NpaNxxX> <NpaNxxX>3105101</NpaNxxX> <NpaNxxX>3109498</NpaNxxX> <NpaNxxX>3109499</NpaNxxX> <NpaNxxX>4242260</NpaNxxX></NpaNxxXs><Id>1</Id> </CoveredRateCenter></CoveredRateCenters>"),
        ]);

        self::$container = [];
        $history = Middleware::history(self::$container);
        $handler = HandlerStack::create($mock);
        $handler->push($history);

        $client = new Iris\Client("test", "test", Array('url' => 'https://api.test.inetwork.com/v1.0', 'handler' => $handler));
        self::$rcs = new Iris\CoveredRateCenters($client);
    }

	public function testTnsGet() {
		$rcs = self::$rcs->getList(["page" => 1, "size" => 10 ]);

        $this->assertEquals(2, count($rcs));

        $json = '{"Name":"AVALON","Abbreviation":"AVALON","State":"CA","Lata":"730","AvailableNumberCount":"1","ZipCodes":{"ZipCode":"90731"},"Cities":{"City":"SAN PEDRO"},"Tiers":{"Tier":"0"},"NpaNxxXs":{"NpaNxxX":["3105100","3105101","3109498","3109499","4242260"]},"Id":"1"}';
		$this->assertEquals($json, json_encode($rcs[0]->to_array()));
        $this->assertEquals("GET", self::$container[self::$index]['request']->getMethod());
        $this->assertEquals("https://api.test.inetwork.com/v1.0/coveredRateCenters?page=1&size=10", self::$container[self::$index]['request']->getUri());
        self::$index++;
    }
    public function testTnGet() {
        $rc = self::$rcs->covered_rate_center("1");

        $json = '{"Name":"AVALON","Abbreviation":"AVALON","State":"CA","Lata":"730","AvailableNumberCount":"1","ZipCodes":{"ZipCode":"90731"},"Cities":{"City":"SAN PEDRO"},"Tiers":{"Tier":"0"},"NpaNxxXs":{"NpaNxxX":["3105100","3105101","3109498","3109499","4242260"]},"Id":"1"}';
		$this->assertEquals($json, json_encode($rc->to_array()));

        $this->assertEquals("GET", self::$container[self::$index]['request']->getMethod());
        $this->assertEquals("https://api.test.inetwork.com/v1.0/coveredRateCenters/1", self::$container[self::$index]['request']->getUri());
        self::$index++;
    }
}
