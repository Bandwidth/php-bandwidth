<?php
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Middleware;

class SubscriptionsTest extends PHPUnit_Framework_TestCase {
    public static $container;
    public static $subscriptions;
    public static $index = 0;

    public static function setUpBeforeClass() {
        $mock = new MockHandler([
            new Response(201, ['Location' => 'https://api.test.inetwork.com:443/v1.0/accounts/9500249/sunscriptions/2489']),
            new Response(200, [], "<?xml version=\"1.0\" encoding=\"UTF-8\" standalone=\"yes\"?><SubscriptionsResponse><Subscriptions><Subscription><SubscriptionId>1</SubscriptionId><OrderType>orders</OrderType><OrderId>8684b1c8-7d41-4877-bfc2-6bd8ea4dc89f</OrderId><EmailSubscription><Email>test@test</Email><DigestRequested>NONE</DigestRequested></EmailSubscription></Subscription></Subscriptions></SubscriptionsResponse>"),
            new Response(200, [], "<?xml version=\"1.0\" encoding=\"UTF-8\" standalone=\"yes\"?><SubscriptionsResponse>    <Subscriptions>        <Subscription>            <SubscriptionId>1c59e661-8c90-4cb5-aab1-00547ea45ecb</SubscriptionId>            <OrderType>portins</OrderType>            <OrderId>98939562-90b0-40e9-8335-5526432d9741</OrderId>            <EmailSubscription>                <Email>test@test.com</Email>                <DigestRequested>DAILY</DigestRequested>            </EmailSubscription>        </Subscription>    </Subscriptions></SubscriptionsResponse>"),
            new Response(200),
            new Response(200),
        ]);

        self::$container = [];
        $history = Middleware::history(self::$container);
        $handler = HandlerStack::create($mock);
        $handler->push($history);

        $client = new Iris\Client("test", "test", Array('url' => 'https://api.test.inetwork.com/v1.0', 'handler' => $handler));
        $account = new Iris\Account(9500249, $client);
        self::$subscriptions = $account->subscriptions();
    }

	public function testSubsCreate() {
		$subscription = self::$subscriptions->create([
            "OrderType" => "portins",
            "OrderId" => "98939562-90b0-40e9-8335-5526432d9741",
            "EmailSubscription" => [
                "Email" => "test@test.com",
                "DigestRequested" => "DAILY"
            ]
        ], false);

        $json = '{"OrderType":"portins","OrderId":"98939562-90b0-40e9-8335-5526432d9741","EmailSubscription":{"Email":"test@test.com","DigestRequested":"DAILY"}}';
		$this->assertEquals($json, json_encode($subscription->to_array()));

        $subscription = self::$subscriptions->create([
            "OrderType" => "portins",
            "OrderId" => "98939562-90b0-40e9-8335-5526432d9741",
            "EmailSubscription" => [
                "Email" => "test@test.com",
                "DigestRequested" => "DAILY"
            ]
        ]);

        $this->assertEquals("2489", $subscription->get_id());
        $this->assertEquals("POST", self::$container[self::$index]['request']->getMethod());
        $this->assertEquals("https://api.test.inetwork.com/v1.0/accounts/9500249/subscriptions", self::$container[self::$index]['request']->getUri());
        self::$index++;
    }

    public function testSubsGet() {
		$subscriptions = self::$subscriptions->getList(["orderType" => "portins"]);

        $this->assertEquals(1, count($subscriptions));
        $this->assertEquals(1, $subscriptions[0]->get_id());
        $this->assertEquals("8684b1c8-7d41-4877-bfc2-6bd8ea4dc89f", $subscriptions[0]->OrderId);
        $this->assertEquals("GET", self::$container[self::$index]['request']->getMethod());
        $this->assertEquals("https://api.test.inetwork.com/v1.0/accounts/9500249/subscriptions?orderType=portins", self::$container[self::$index]['request']->getUri());
        self::$index++;
    }

    public function testSubGet() {
		$subscriptions = self::$subscriptions->subscription("1");

        $this->assertEquals("GET", self::$container[self::$index]['request']->getMethod());
        $this->assertEquals("https://api.test.inetwork.com/v1.0/accounts/9500249/subscriptions/1", self::$container[self::$index]['request']->getUri());
        self::$index++;
    }

    public function testSubPut() {
        $subscription = self::$subscriptions->create([
            "SubscriptionId" => "1c59e661-8c90-4cb5-aab1-00547ea45ecb",
            "OrderType" => "portins",
            "OrderId" => "98939562-90b0-40e9-8335-5526432d9741",
            "EmailSubscription" => [
                "Email" => "test@test.com",
                "DigestRequested" => "DAILY"
            ]
        ], false);

        $json = '{"SubscriptionId":"1c59e661-8c90-4cb5-aab1-00547ea45ecb","OrderType":"portins","OrderId":"98939562-90b0-40e9-8335-5526432d9741","EmailSubscription":{"Email":"test@test.com","DigestRequested":"DAILY"}}';
		$this->assertEquals($json, json_encode($subscription->to_array()));

        $subscription->update();

        $this->assertEquals("PUT", self::$container[self::$index]['request']->getMethod());
        $this->assertEquals("https://api.test.inetwork.com/v1.0/accounts/9500249/subscriptions/1c59e661-8c90-4cb5-aab1-00547ea45ecb", self::$container[self::$index]['request']->getUri());
        self::$index++;

    }
    public function testSubDelete() {
        $subscription = self::$subscriptions->create([
            "SubscriptionId" => "1c59e661-8c90-4cb5-aab1-00547ea45ecb"
        ], false);

        $subscription->delete();

        $this->assertEquals("DELETE", self::$container[self::$index]['request']->getMethod());
        $this->assertEquals("https://api.test.inetwork.com/v1.0/accounts/9500249/subscriptions/1c59e661-8c90-4cb5-aab1-00547ea45ecb", self::$container[self::$index]['request']->getUri());
        self::$index++;

    }


}
