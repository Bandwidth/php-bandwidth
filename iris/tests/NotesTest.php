<?php

use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Middleware;

class NotesTest extends PHPUnit_Framework_TestCase {
    public static $container;
    public static $notes;
    public static $index = 0;

    public static function setUpBeforeClass() {
        $mock = new MockHandler([
            new Response(200, ['Location' => 'https://api.test.inetwork.com/v1.0/accounts/9500249/disconnects/b902dee1-0585-4258-becd-5c7e51ccf5e1/notes/123']),
            new Response(200, [], "<?xml version=\"1.0\" encoding=\"UTF-8\" standalone=\"yes\"?><Notes>    <Note>        <Id>11425</Id>        <UserId>byo_dev</UserId>        <Description>Test Note</Description>        <LastDateModifier>2015-06-18T04:19:59.000Z</LastDateModifier>    </Note></Notes>"),
            new Response(200, [], "<?xml version=\"1.0\" encoding=\"UTF-8\" standalone=\"yes\"?><Notes>    <Note>        <Id>11425</Id>        <UserId>byo_dev</UserId>        <Description>Test Note</Description>        <LastDateModifier>2015-06-18T04:19:59.000Z</LastDateModifier>    </Note><Note>        <Id>11425</Id>        <UserId>byo_dev</UserId>        <Description>Test Note</Description>        <LastDateModifier>2015-06-18T04:19:59.000Z</LastDateModifier>    </Note></Notes>")
        ]);

        self::$container = [];
        $history = Middleware::history(self::$container);
        $handler = HandlerStack::create($mock);
        $handler->push($history);

        $client = new Iris\Client("test", "test", Array('url' => 'https://api.test.inetwork.com/v1.0', 'handler' => $handler));
        $account = new Iris\Account(9500249, $client);
        self::$notes = $account->disconnects()->create(array("OrderId" => "b902dee1-0585-4258-becd-5c7e51ccf5e1"), false)->notes();
    }

    public function testNoteCreate() {
        $note = self::$notes->create(array(
            "UserId" => "byo_dev",
            "Description" => "Test Note"
        ));

        $this->assertEquals("123", $note->Id);
        $this->assertEquals("POST", self::$container[self::$index]['request']->getMethod());
        $this->assertEquals("https://api.test.inetwork.com/v1.0/accounts/9500249/disconnects/b902dee1-0585-4258-becd-5c7e51ccf5e1/notes", self::$container[self::$index]['request']->getUri());
        self::$index++;
    }

    public function testNotesGet() {
        $notes = self::$notes->getList();

        $this->assertEquals(1, count($notes));
        $this->assertEquals("11425", $notes[0]->Id);
        $this->assertEquals("GET", self::$container[self::$index]['request']->getMethod());
        $this->assertEquals("https://api.test.inetwork.com/v1.0/accounts/9500249/disconnects/b902dee1-0585-4258-becd-5c7e51ccf5e1/notes", self::$container[self::$index]['request']->getUri());
        self::$index++;
    }

    public function testNotesGetTwoItems() {
        $notes = self::$notes->getList();

        $this->assertEquals(2, count($notes));
        $this->assertEquals("11425", $notes[0]->Id);
        self::$index++;
    }
}
