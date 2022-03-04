<?php

namespace GuzzleHttp\Tests\Oauth1;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Subscriber\Oauth\Oauth1;

class Oauth1Test extends \PHPUnit_Framework_TestCase
{
    const TIMESTAMP = '1327274290';
    const NONCE = 'e7aa11195ca58349bec8b5ebe351d3497eb9e603';

    private $config = [
        'consumer_key'    => 'foo',
        'consumer_secret' => 'bar',
        'token'           => 'count',
        'token_secret'    => 'dracula'
    ];

    public function testAcceptsConfigurationData()
    {
        $p = new Oauth1($this->config);

        // Access the config object
        $class = new \ReflectionClass($p);
        $property = $class->getProperty('config');
        $property->setAccessible(true);
        $config = $property->getValue($p);

        $this->assertEquals('foo', $config['consumer_key']);
        $this->assertEquals('bar', $config['consumer_secret']);
        $this->assertEquals('count', $config['token']);
        $this->assertEquals('dracula', $config['token_secret']);
        $this->assertEquals('1.0', $config['version']);
        $this->assertEquals('HMAC-SHA1', $config['signature_method']);
        $this->assertEquals('header', $config['request_method']);
    }

    public function testCreatesStringToSignFromPostRequest()
    {
        $stack = HandlerStack::create();

        $middleware = new Oauth1($this->config);
        $stack->push($middleware);

        $container = [];
        $history = Middleware::history($container);
        $stack->push($history);

        $client = new Client([
            'handler' => $stack
        ]);

        $client->post('http://httpbin.org/post', [
            'auth' => 'oauth',
            'form_params' => [
                'foo' => [
                    'baz'  => ['bar'],
                    'bam'  => [null, true, false]
                ]
            ]
        ]);

        /* @var Request $request */
        $request = $container[0]['request'];

        $this->assertTrue($request->hasHeader('Authorization'));
    }

    public function testSignsPlainText()
    {
        $config = $this->config;
        $config['signature_method'] = Oauth1::SIGNATURE_METHOD_PLAINTEXT;

        $stack = HandlerStack::create();

        $middleware = new Oauth1($config);
        $stack->push($middleware);

        $container = [];
        $history = Middleware::history($container);
        $stack->push($history);

        $client = new Client([
            'handler' => $stack
        ]);

        $client->get('http://httpbin.org', ['auth' => 'oauth']);

        /* @var Request $request */
        $request = $container[0]['request'];

        $this->assertTrue($request->hasHeader('Authorization'));
        $this->assertContains('oauth_signature_method="PLAINTEXT"', $request->getHeader('Authorization')[0]);
        $this->assertContains('oauth_signature="', $request->getHeader('Authorization')[0]);
    }

    public function testSignsOauthRequestsInHeader()
    {
        $stack = HandlerStack::create();

        $middleware = new Oauth1($this->config);
        $stack->push($middleware);

        $container = [];
        $history = Middleware::history($container);
        $stack->push($history);

        $client = new Client([
            'handler' => $stack
        ]);

        $client->post('http://httpbin.org/post', [
            'auth' => 'oauth',
        ]);

        /* @var Request $request */
        $request = $container[0]['request'];

        $this->assertTrue($request->hasHeader('Authorization'));
        $this->assertCount(0, \GuzzleHttp\Psr7\parse_query($request->getUri()->getQuery()));
        $check = ['oauth_consumer_key', 'oauth_nonce', 'oauth_signature',
            'oauth_signature_method', 'oauth_timestamp', 'oauth_token',
            'oauth_version'];
        foreach ($check as $name) {
            $this->assertContains($name . '=', $request->getHeader('Authorization')[0]);
        }
    }

    public function testSignsOauthQueryStringRequest()
    {
        $config = $this->config;
        $config['request_method'] = Oauth1::REQUEST_METHOD_QUERY;

        $stack = HandlerStack::create();

        $middleware = new Oauth1($config);
        $stack->push($middleware);

        $container = [];
        $history = Middleware::history($container);
        $stack->push($history);

        $client = new Client([
            'handler' => $stack
        ]);

        $client->get('http://httpbin.org', ['auth' => 'oauth']);

        /* @var Request $request */
        $request = $container[0]['request'];

        $this->assertFalse($request->hasHeader('Authorization'));
        $check = ['oauth_consumer_key', 'oauth_nonce', 'oauth_signature',
            'oauth_signature_method', 'oauth_timestamp', 'oauth_token',
            'oauth_version'];
        foreach ($check as $name) {
            $this->assertNotEmpty(\GuzzleHttp\Psr7\parse_query($request->getUri()->getQuery())[$name]);
        }

        // Ensure that no extra keys were added
        $keys = array_keys(\GuzzleHttp\Psr7\parse_query($request->getUri()->getQuery()));
        sort($keys);
        $this->assertSame($keys, $check);
    }

    public function testOnlyTouchesWhenAuthConfigIsOauth()
    {
        $stack = HandlerStack::create();

        $middleware = new Oauth1($this->config);
        $stack->push($middleware);

        $container = [];
        $history = Middleware::history($container);
        $stack->push($history);

        $client = new Client([
            'handler' => $stack
        ]);

        $client->get('http://httpbin.org');

        /* @var Request $request */
        $request = $container[0]['request'];

        $this->assertCount(0, \GuzzleHttp\Psr7\parse_query($request->getUri()->getQuery()));
        $this->assertEmpty($request->getHeader('Authorization'));
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testValidatesRequestMethod()
    {
        $stack = HandlerStack::create();

        $config = $this->config;
        $config['request_method'] = 'Foo';

        $middleware = new Oauth1($config);
        $stack->push($middleware);

        $client = new Client([
            'handler' => $stack
        ]);

        $client->get('http://httpbin.org', ['auth' => 'oauth']);
    }

    /**
     * @expectedException \RuntimeException
     */
    public function testExceptionOnSignatureError()
    {
        $stack = HandlerStack::create();

        $config = $this->config;
        $config['signature_method'] = 'Foo';

        $middleware = new Oauth1($config);
        $stack->push($middleware);

        $client = new Client([
            'handler' => $stack
        ]);

        $client->get('http://httpbin.org', ['auth' => 'oauth']);
    }

    public function testDoesNotAddEmptyValuesToAuthorization()
    {
        $config = $this->config;
        unset($config['token']);

        $stack = HandlerStack::create();

        $middleware = new Oauth1($config);
        $stack->push($middleware);

        $container = [];
        $history = Middleware::history($container);
        $stack->push($history);

        $client = new Client([
            'handler' => $stack
        ]);

        $client->get('http://httpbin.org', ['auth' => 'oauth']);

        /* @var Request $request */
        $request = $container[0]['request'];

        $this->assertTrue($request->hasHeader('Authorization'));
        $this->assertNotContains('oauth_token=', $request->getHeader('Authorization')[0]);
    }

    public function testRandomParametersAreNotAutomaticallyAdded()
    {
        $config = $this->config;
        $config['foo'] = 'bar';

        $stack = HandlerStack::create();

        $middleware = new Oauth1($config);
        $stack->push($middleware);

        $container = [];
        $history = Middleware::history($container);
        $stack->push($history);

        $client = new Client([
            'handler' => $stack
        ]);

        $client->get('http://httpbin.org', ['auth' => 'oauth']);

        /* @var Request $request */
        $request = $container[0]['request'];

        $this->assertTrue($request->hasHeader('Authorization'));
        $this->assertNotContains('foo=bar', $request->getHeader('Authorization')[0]);
    }

    public function testAllowsRealm()
    {
        $config = $this->config;
        $config['realm'] = 'foo';

        $stack = HandlerStack::create();

        $middleware = new Oauth1($config);
        $stack->push($middleware);

        $container = [];
        $history = Middleware::history($container);
        $stack->push($history);

        $client = new Client([
            'handler' => $stack
        ]);

        $client->get('http://httpbin.org', ['auth' => 'oauth']);

        /* @var Request $request */
        $request = $container[0]['request'];

        $this->assertTrue($request->hasHeader('Authorization'));
        $this->assertContains('OAuth realm="foo",', $request->getHeader('Authorization')[0]);
    }

    public function testTwitterIntegration()
    {
        if (empty(getenv('OAUTH_CONSUMER_SECRET'))) {
            $this->markTestSkipped('No OAUTH_CONSUMER_SECRET provided in phpunit.xml');
            return;
        }

        $config = $this->config;
        $config['consumer_key']    = getenv('OAUTH_CONSUMER_KEY');
        $config['consumer_secret'] = getenv('OAUTH_CONSUMER_SECRET');
        $config['token']           = getenv('OAUTH_TOKEN');
        $config['token_secret']    = getenv('OAUTH_TOKEN_SECRET');

        $stack = HandlerStack::create();

        $middleware = new Oauth1($config);
        $stack->push($middleware);

        $container = [];
        $history = Middleware::history($container);
        $stack->push($history);

        $client = new Client([
            'handler' => $stack
        ]);

        try {
            $client->get('https://api.twitter.com/1.1/account/settings.json', ['auth' => 'oauth']);
        } catch (ClientException $e) {
            if ($e->getResponse()->getStatusCode() == 429) {
                $this->markTestIncomplete('You are being throttled');
            } else {
                throw $e;
            }
        }
    }

    public function testTwitterStreamingIntegration()
    {
        if (empty(getenv('OAUTH_CONSUMER_SECRET'))) {
            $this->markTestSkipped('No OAUTH_CONSUMER_SECRET provided in phpunit.xml');
            return;
        }

        $config = $this->config;
        $config['consumer_key']    = $_SERVER['OAUTH_CONSUMER_KEY'];
        $config['consumer_secret'] = $_SERVER['OAUTH_CONSUMER_SECRET'];
        $config['token']           = $_SERVER['OAUTH_TOKEN'];
        $config['token_secret']    = $_SERVER['OAUTH_TOKEN_SECRET'];

        $stack = HandlerStack::create();

        $middleware = new Oauth1($config);
        $stack->push($middleware);

        $container = [];
        $history = Middleware::history($container);
        $stack->push($history);

        $client = new Client([
            'base_uri' => 'https://stream.twitter.com/1.1/',
            'handler' => $stack,
            'auth' => 'oauth'
        ]);

        try {
            $response = $client->post('statuses/filter.json', [
                'query'   => ['track' => 'bieber'],
                'stream' => true
            ]);
            $body = $response->getBody()->getContents();
            $this->assertContains('bieber', strtolower($body));
            $this->assertNotEmpty(json_decode($body, true));
        } catch (ClientException $e) {
            if ($e->getResponse()->getStatusCode() == 429) {
                $this->markTestIncomplete('You are being throttled');
            } else {
                throw $e;
            }
        }
    }
}
