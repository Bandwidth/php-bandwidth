<?php
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Middleware;

class SippeersTest extends PHPUnit_Framework_TestCase {
    public static $container;
    public static $sippeers;
    public static $index = 0;

    public static function setUpBeforeClass() {
        $mock = new MockHandler([
            new Response(201, ['Location' => 'https://api.test.inetwork.com:443/v1.0/accounts/9500249/sites/2489/sippeers/9091']),
			new Response(200, [], "<?xml version=\"1.0\" encoding=\"UTF-8\" standalone=\"yes\"?><TNSipPeersResponse>    <SipPeers>        <SipPeer>            <PeerId>500709</PeerId>            <PeerName>Test4 Peer</PeerName>            <IsDefaultPeer>true</IsDefaultPeer>            <ShortMessagingProtocol>SMPP</ShortMessagingProtocol>            <VoiceHosts>                <Host>                    <HostName>192.168.181.94</HostName>                </Host>            </VoiceHosts>            <VoiceHostGroups/>            <SmsHosts>                <Host>                    <HostName>192.168.181.94</HostName>                </Host>            </SmsHosts>            <TerminationHosts>                <TerminationHost>                    <HostName>192.168.181.94</HostName>                    <Port>0</Port>                    <CustomerTrafficAllowed>DOMESTIC</CustomerTrafficAllowed>                    <DataAllowed>true</DataAllowed>                </TerminationHost>            </TerminationHosts>        </SipPeer>        <SipPeer>            <PeerId>500705</PeerId>            <PeerName>Test2 Peer</PeerName>            <IsDefaultPeer>false</IsDefaultPeer>            <ShortMessagingProtocol>SMPP</ShortMessagingProtocol>            <VoiceHosts>                <Host>                    <HostName>192.168.181.98</HostName>                </Host>            </VoiceHosts>            <VoiceHostGroups/>            <SmsHosts>                <Host>                    <HostName>192.168.181.98</HostName>                </Host>            </SmsHosts>            <TerminationHosts>                <TerminationHost>                    <HostName>192.168.181.98</HostName>                    <Port>0</Port>                    <CustomerTrafficAllowed>DOMESTIC</CustomerTrafficAllowed>                    <DataAllowed>true</DataAllowed>                </TerminationHost>            </TerminationHosts>        </SipPeer>    </SipPeers></TNSipPeersResponse>    "),
			new Response(200, [], "<?xml version=\"1.0\" encoding=\"UTF-8\" standalone=\"yes\"?><TNSipPeersResponse>    <SipPeers>        <SipPeer>            <PeerId>500709</PeerId>            <PeerName>Test4 Peer</PeerName>            <IsDefaultPeer>true</IsDefaultPeer>            <ShortMessagingProtocol>SMPP</ShortMessagingProtocol>            <VoiceHosts>                <Host>                    <HostName>192.168.181.94</HostName>                </Host>            </VoiceHosts>            <VoiceHostGroups/>            <SmsHosts>                <Host>                    <HostName>192.168.181.94</HostName>                </Host>            </SmsHosts>            <TerminationHosts>                <TerminationHost>                    <HostName>192.168.181.94</HostName>                    <Port>0</Port>                    <CustomerTrafficAllowed>DOMESTIC</CustomerTrafficAllowed>                    <DataAllowed>true</DataAllowed>                </TerminationHost>            </TerminationHosts>        </SipPeer>    </SipPeers></TNSipPeersResponse>    "),
			new Response(200, [], "<?xml version=\"1.0\" encoding=\"UTF-8\" standalone=\"yes\"?><SipPeerResponse>    <SipPeer>        <PeerId>500651</PeerId>        <PeerName>Something</PeerName>        <IsDefaultPeer>false</IsDefaultPeer>        <ShortMessagingProtocol>SMPP</ShortMessagingProtocol>        <VoiceHosts>            <Host>                <HostName>192.168.181.2</HostName>            </Host>        </VoiceHosts>        <VoiceHostGroups/>        <SmsHosts>            <Host>                <HostName>192.168.181.2</HostName>            </Host>        </SmsHosts>        <TerminationHosts>            <TerminationHost>                <HostName>192.168.181.2</HostName>                <Port>0</Port>                <CustomerTrafficAllowed>DOMESTIC</CustomerTrafficAllowed>                <DataAllowed>true</DataAllowed>            </TerminationHost>        </TerminationHosts>    </SipPeer></SipPeerResponse>"),
			new Response(200),
			new Response(200),
            new Response(200),
            new Response(200),
            new Response(200),
            new Response(200, [], "<?xml version=\"1.0\" encoding=\"UTF-8\" standalone=\"yes\"?><SipPeerTelephoneNumbersResponse>    <SipPeerTelephoneNumbers>        <SipPeerTelephoneNumber>            <FullNumber>8183386251</FullNumber>        </SipPeerTelephoneNumber>        <SipPeerTelephoneNumber>            <FullNumber>8183386252</FullNumber>        </SipPeerTelephoneNumber>        <SipPeerTelephoneNumber>            <FullNumber>8183386249</FullNumber>        </SipPeerTelephoneNumber>        <SipPeerTelephoneNumber>            <FullNumber>8183386247</FullNumber>        </SipPeerTelephoneNumber>    </SipPeerTelephoneNumbers></SipPeerTelephoneNumbersResponse>"),
            new Response(200, [], "<?xml version=\"1.0\" encoding=\"UTF-8\" standalone=\"yes\"?><SipPeerTelephoneNumbersCountResponse>    <SipPeerTelephoneNumbersCounts>        <SipPeerTelephoneNumbersCount>4</SipPeerTelephoneNumbersCount>    </SipPeerTelephoneNumbersCounts></SipPeerTelephoneNumbersCountResponse>"),
        ]);

        self::$container = [];
        $history = Middleware::history(self::$container);
        $handler = HandlerStack::create($mock);
        $handler->push($history);

        $client = new Iris\Client("test", "test", Array('url' => 'https://api.test.inetwork.com/v1.0', 'handler' => $handler));
        $account = new Iris\Account(9500249, $client);
        $site = $account->sites()->create(["Id" => "9999"], false);
		self::$sippeers = $site->sippeers();
    }

    public function testSippeerCreate() {
		$sippeer = self::$sippeers->create(array(
				"PeerName" => "Test5 Peer",
				"IsDefaultPeer" => false,
				"ShortMessagingProtocol" => "SMPP",
				"VoiceHosts" => array(
					"Host" => array(
						"HostName" => "192.168.181.90"
					)
				),
				"SmsHosts" => array(
					"Host" => array(
						"HostName" => "192.168.181.90"
					)
				),
				"TerminationHosts" => array(
					"TerminationHost" => array(
						"HostName" => "192.168.181.90",
						"Port" => 0,
						"CustomerTrafficAllowed" => "DOMESTIC",
						"DataAllowed" => true
					)
				)
		));

        $this->assertEquals("9091", $sippeer->PeerId);
        $this->assertEquals("POST", self::$container[self::$index]['request']->getMethod());
        $this->assertEquals("https://api.test.inetwork.com/v1.0/accounts/9500249/sites/9999/sippeers", self::$container[self::$index]['request']->getUri());
        self::$index++;
    }

	public function testSippeersGet() {
		$sippeers = self::$sippeers->getList();

        $this->assertEquals(2, count($sippeers));
		$this->assertEquals("500709", $sippeers[0]->PeerId);
        $this->assertEquals("GET", self::$container[self::$index]['request']->getMethod());
        $this->assertEquals("https://api.test.inetwork.com/v1.0/accounts/9500249/sites/9999/sippeers", self::$container[self::$index]['request']->getUri());
        self::$index++;
    }

	public function testSippeersGetOne() {
		$sippeers = self::$sippeers->getList();

        $this->assertEquals(1, count($sippeers));
		$this->assertEquals("500709", $sippeers[0]->PeerId);
        $this->assertEquals("GET", self::$container[self::$index]['request']->getMethod());
        $this->assertEquals("https://api.test.inetwork.com/v1.0/accounts/9500249/sites/9999/sippeers", self::$container[self::$index]['request']->getUri());
        self::$index++;
    }

	public function testSippeerGet() {
		$sippeer = self::$sippeers->sippeer("500651");

		$this->assertEquals("500651", $sippeer->PeerId);
		$this->assertEquals("192.168.181.2", $sippeer->VoiceHosts->Host->HostName);
        $this->assertEquals("GET", self::$container[self::$index]['request']->getMethod());
        $this->assertEquals("https://api.test.inetwork.com/v1.0/accounts/9500249/sites/9999/sippeers/500651", self::$container[self::$index]['request']->getUri());
        self::$index++;
    }

	public function testSippeerUpdate() {
		$sippeer = self::$sippeers->create(
			array("PeerId" => "2489"), false
		);

        $sippeer->update();

        $this->assertEquals("PUT", self::$container[self::$index]['request']->getMethod());
        $this->assertEquals("https://api.test.inetwork.com/v1.0/accounts/9500249/sites/9999/sippeers/2489", self::$container[self::$index]['request']->getUri());
        self::$index++;
    }


	public function testSippeerDelete() {
		$sippeer = self::$sippeers->create(
			array("PeerId" => "2489"), false
		);

        $sippeer->delete();

		$this->assertEquals("DELETE", self::$container[self::$index]['request']->getMethod());
		$this->assertEquals("https://api.test.inetwork.com/v1.0/accounts/9500249/sites/9999/sippeers/2489", self::$container[self::$index]['request']->getUri());
		self::$index++;
	}

    public function testMoveTNs() {
        $sippeer = self::$sippeers->create(
			array("PeerId" => "2489"), false
		);

        $sippeer->movetns([
            "FullNumber" => [ "9192000046", "9192000047", "9192000048" ]
        ]);

        $this->assertEquals("POST", self::$container[self::$index]['request']->getMethod());
        $this->assertEquals("https://api.test.inetwork.com/v1.0/accounts/9500249/sites/9999/sippeers/2489/movetns", self::$container[self::$index]['request']->getUri());
        self::$index++;
    }

    public function testTNOptions() {
        $sippeer = self::$sippeers->create(
			array("PeerId" => "2489"), false
		);

        $sippeer->tns()->create(["FullNumber" => "8183386251"], false)->set_tn_options([
            "FullNumber" => "8183386251",
            "CallForward" => "9194394706",
            "RewriteUser" => "JohnDoe",
            "NumberFormat" => "10digit",
            "RPIDFormat" => "e164"
        ]);

        $this->assertEquals("POST", self::$container[self::$index]['request']->getMethod());
        $this->assertEquals("https://api.test.inetwork.com/v1.0/accounts/9500249/sites/9999/sippeers/2489/tns/8183386251", self::$container[self::$index]['request']->getUri());
        self::$index++;
    }

    public function testGetTN() {
        $sippeer = self::$sippeers->create(
            array("PeerId" => "2489"), false
        );

        $tn = $sippeer->tns()->tn("8183386251");

        $this->assertEquals("8183386251", $tn->FullNumber);
        $this->assertEquals("GET", self::$container[self::$index]['request']->getMethod());
        $this->assertEquals("https://api.test.inetwork.com/v1.0/accounts/9500249/sites/9999/sippeers/2489/tns/8183386251", self::$container[self::$index]['request']->getUri());
        self::$index++;
    }

    public function testGetTNs() {
        $sippeer = self::$sippeers->create(
            array("PeerId" => "2489"), false
        );

        $tns = $sippeer->tns()->getList();

        $this->assertEquals(4, count($tns));
        $this->assertEquals("8183386251", $tns[0]->FullNumber);
        $this->assertEquals("GET", self::$container[self::$index]['request']->getMethod());
        $this->assertEquals("https://api.test.inetwork.com/v1.0/accounts/9500249/sites/9999/sippeers/2489/tns", self::$container[self::$index]['request']->getUri());
        self::$index++;
    }

    public function testTotaltns() {
        $sippeer = self::$sippeers->create(
            array("PeerId" => "2489"), false
        );

        $count = $sippeer->totaltns();

        $this->assertEquals(4, $count);
        $this->assertEquals("GET", self::$container[self::$index]['request']->getMethod());
        $this->assertEquals("https://api.test.inetwork.com/v1.0/accounts/9500249/sites/9999/sippeers/2489/totaltns", self::$container[self::$index]['request']->getUri());
        self::$index++;
    }

}
