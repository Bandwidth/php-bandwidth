<?php
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Middleware;

class UsersTest extends PHPUnit_Framework_TestCase {
    public static $container;
    public static $client;
    public static $account;
    public static $index = 0;

    public static function setUpBeforeClass() {
        $mock = new MockHandler([
            new Response(200, [], "<?xml version=\"1.0\" encoding=\"UTF-8\" standalone=\"yes\"?><UsersResponse><Users><User><Username>byo_dev</Username><FirstName>test</FirstName><LastName>test</LastName><EmailAddress>jsommerset@bandwidth.com</EmailAddress><TelephoneNumber>5413637598</TelephoneNumber><Roles><Role><RoleName>ROLE_USER</RoleName><Permissions><Permission><PermissionName>UPDATE</PermissionName></Permission><Permission><PermissionName>VIEW</PermissionName></Permission></Permissions></Role><Role><RoleName>ROLE_BDR</RoleName><Permissions><Permission><PermissionName>UPDATE</PermissionName></Permission><Permission><PermissionName>VIEW</PermissionName></Permission></Permissions></Role><Role><RoleName>ROLE_API_HISTORY</RoleName><Permissions><Permission><PermissionName>UPDATE</PermissionName></Permission><Permission><PermissionName>VIEW</PermissionName></Permission></Permissions></Role><Role><RoleName>ROLE_API_SITE</RoleName><Permissions><Permission><PermissionName>UPDATE</PermissionName></Permission><Permission><PermissionName>VIEW</PermissionName></Permission></Permissions></Role><Role><RoleName>ROLE_API_SEARCH</RoleName><Permissions><Permission><PermissionName>VIEW</PermissionName></Permission></Permissions></Role><Role><RoleName>ROLE_API_ORDERING</RoleName><Permissions><Permission><PermissionName>UPDATE</PermissionName></Permission><Permission><PermissionName>VIEW</PermissionName></Permission></Permissions></Role><Role><RoleName>ROLE_API_PROFILE</RoleName><Permissions><Permission><PermissionName>UPDATE</PermissionName></Permission><Permission><PermissionName>VIEW</PermissionName></Permission></Permissions></Role><Role><RoleName>ROLE_API_LNP</RoleName><Permissions><Permission><PermissionName>UPDATE</PermissionName></Permission><Permission><PermissionName>VIEW</PermissionName></Permission></Permissions></Role><Role><RoleName>ROLE_API_ACCOUNT</RoleName><Permissions><Permission><PermissionName>VIEW</PermissionName></Permission></Permissions></Role><Role><RoleName>ROLE_API_DLDA</RoleName><Permissions><Permission><PermissionName>UPDATE</PermissionName></Permission><Permission><PermissionName>VIEW</PermissionName></Permission></Permissions></Role><Role><RoleName>ROLE_API_CNAMLIDB</RoleName><Permissions><Permission><PermissionName>UPDATE</PermissionName></Permission><Permission><PermissionName>VIEW</PermissionName></Permission></Permissions></Role></Roles></User></Users></UsersResponse>"),
            new Response(200, [], ""),
        ]);

        self::$container = [];
        $history = Middleware::history(self::$container);
        $handler = HandlerStack::create($mock);
        $handler->push($history);

        self::$client = new Iris\Client("test", "test", Array('url' => 'https://api.test.inetwork.com/v1.0', 'handler' => $handler));
        self::$account = new \Iris\Account(9500249, self::$client);
    }

    public function testUsersGet() {
        $users = self::$account->users()->getList();

        $json = '{"Username":"byo_dev","FirstName":"test","LastName":"test","EmailAddress":"jsommerset@bandwidth.com","TelephoneNumber":"5413637598","Roles":{"Role":[{"RoleName":"ROLE_USER","Permissions":{"Permission":[{"PermissionName":"UPDATE"},{"PermissionName":"VIEW"}]}},{"RoleName":"ROLE_BDR","Permissions":{"Permission":[{"PermissionName":"UPDATE"},{"PermissionName":"VIEW"}]}},{"RoleName":"ROLE_API_HISTORY","Permissions":{"Permission":[{"PermissionName":"UPDATE"},{"PermissionName":"VIEW"}]}},{"RoleName":"ROLE_API_SITE","Permissions":{"Permission":[{"PermissionName":"UPDATE"},{"PermissionName":"VIEW"}]}},{"RoleName":"ROLE_API_SEARCH","Permissions":{"Permission":{"PermissionName":"VIEW"}}},{"RoleName":"ROLE_API_ORDERING","Permissions":{"Permission":[{"PermissionName":"UPDATE"},{"PermissionName":"VIEW"}]}},{"RoleName":"ROLE_API_PROFILE","Permissions":{"Permission":[{"PermissionName":"UPDATE"},{"PermissionName":"VIEW"}]}},{"RoleName":"ROLE_API_LNP","Permissions":{"Permission":[{"PermissionName":"UPDATE"},{"PermissionName":"VIEW"}]}},{"RoleName":"ROLE_API_ACCOUNT","Permissions":{"Permission":{"PermissionName":"VIEW"}}},{"RoleName":"ROLE_API_DLDA","Permissions":{"Permission":[{"PermissionName":"UPDATE"},{"PermissionName":"VIEW"}]}},{"RoleName":"ROLE_API_CNAMLIDB","Permissions":{"Permission":[{"PermissionName":"UPDATE"},{"PermissionName":"VIEW"}]}}]}}';
        $this->assertEquals($json, json_encode($users[0]->to_array()));
        $this->assertEquals("GET", self::$container[self::$index]['request']->getMethod());
        $this->assertEquals("https://api.test.inetwork.com/v1.0/accounts/9500249/users", self::$container[self::$index]['request']->getUri());
        self::$index++;
    }

    public function testUserPassword() {
        $user = new \Iris\User(self::$client, ["Username" => "byo_dev"]);
        $user->password("123");

        $this->assertEquals("PUT", self::$container[self::$index]['request']->getMethod());
        $this->assertEquals("https://api.test.inetwork.com/v1.0/users/byo_dev/password", self::$container[self::$index]['request']->getUri());
        self::$index++;
    }

}
