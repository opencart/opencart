<?php

namespace GuzzleHttp\Tests\Oauth1;

use GuzzleHttp\Transaction;
use GuzzleHttp\Client;
use GuzzleHttp\Event\BeforeEvent;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Message\Request;
use GuzzleHttp\Post\PostBody;
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

    protected function getRequest()
    {
        $body = new PostBody();
        $body->setField('e', 'f');

        return new Request('POST', 'http://www.test.com/path?a=b&c=d', [], $body);
    }

    public function testSubscribesToEvents()
    {
        $events = (new Oauth1([]))->getEvents();
        $this->assertArrayHasKey('before', $events);
    }

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
        $s = new Oauth1($this->config);
        $client = new Client();
        $request = $client->createRequest('POST', 'http://httpbin.org', [
            'auth' => 'oauth',
            'body' => [
                'foo' => [
                    'baz'  => ['bar'],
                    'bam'  => [null, true, false]
                ]
            ]
        ]);
        $before = new BeforeEvent(new Transaction($client, $request));
        $s->onBefore($before);
        $this->assertTrue($request->hasHeader('Authorization'));
    }

    public function testSignsPlainText()
    {
        $config = $this->config;
        $config['signature_method'] = Oauth1::SIGNATURE_METHOD_PLAINTEXT;
        $s = new Oauth1($config);
        $client = new Client();
        $request = $client->createRequest('GET', 'http://httpbin.org', ['auth' => 'oauth']);
        $before = new BeforeEvent(new Transaction($client, $request));
        $s->onBefore($before);
        $this->assertTrue($request->hasHeader('Authorization'));
        $this->assertContains('oauth_signature_method="PLAINTEXT"', $request->getHeader('Authorization'));
        $this->assertContains('oauth_signature="', $request->getHeader('Authorization'));
    }

    public function testSignsOauthRequestsInHeader()
    {
        $s = new Oauth1($this->config);
        $client = new Client();
        $request = $client->createRequest('GET', 'http://httpbin.org', ['auth' => 'oauth']);
        $before = new BeforeEvent(new Transaction($client, $request));
        $s->onBefore($before);
        $this->assertTrue($request->hasHeader('Authorization'));
        $this->assertCount(0, $request->getQuery());
        $check = ['oauth_consumer_key', 'oauth_nonce', 'oauth_signature',
            'oauth_signature_method', 'oauth_timestamp', 'oauth_token',
            'oauth_version'];
        foreach ($check as $name) {
            $this->assertContains($name . '=', $request->getHeader('Authorization'));
        }
    }

    public function testSignsOauthQueryStringRequest()
    {
        $config = $this->config;
        $config['request_method'] = Oauth1::REQUEST_METHOD_QUERY;
        $s = new Oauth1($config);
        $client = new Client();
        $request = $client->createRequest('GET', 'http://httpbin.org', ['auth' => 'oauth']);
        $before = new BeforeEvent(new Transaction($client, $request));
        $s->onBefore($before);
        $this->assertFalse($request->hasHeader('Authorization'));
        $check = ['oauth_consumer_key', 'oauth_nonce', 'oauth_signature',
            'oauth_signature_method', 'oauth_timestamp', 'oauth_token',
            'oauth_version'];
        foreach ($check as $name) {
            $this->assertNotEmpty($request->getQuery()[$name]);
        }
        // Ensure that no extra keys were added
        $keys = $request->getQuery()->getKeys();
        sort($keys);
        $this->assertSame($keys, $check);
    }

    public function testOnlyTouchesWhenAuthConfigIsOauth()
    {
        $s = new Oauth1($this->config);
        $client = new Client();
        $request = $client->createRequest('GET', 'http://httpbin.org');
        $before = new BeforeEvent(new Transaction($client, $request));
        $s->onBefore($before);
        $this->assertCount(0, $request->getQuery());
        $this->assertEmpty($request->getHeader('Authorization'));
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testValidatesRequestMethod()
    {
        $config = $this->config;
        $config['request_method'] = 'Foo';
        $s = new Oauth1($config);
        $client = new Client();
        $before = new BeforeEvent(new Transaction(
            $client,
            $client->createRequest('GET', 'http://httpbin.org', ['auth' => 'oauth'])
        ));
        $s->onBefore($before);
    }

    /**
     * @expectedException \RuntimeException
     */
    public function testExceptionOnSignatureError()
    {
        $config = $this->config;
        $config['signature_method'] = 'Foo';
        $s = new Oauth1($config);
        $client = new Client();
        $before = new BeforeEvent(new Transaction(
            $client,
            $client->createRequest('GET', 'http://httpbin.org', ['auth' => 'oauth'])
        ));
        $s->onBefore($before);
    }

    public function testDoesNotAddEmptyValuesToAuthorization()
    {
        $config = $this->config;
        unset($config['token']);
        $s = new Oauth1($config);
        $client = new Client();
        $request = $client->createRequest('GET', 'http://httpbin.org', ['auth' => 'oauth']);
        $before = new BeforeEvent(new Transaction($client, $request));
        $s->onBefore($before);
        $this->assertTrue($request->hasHeader('Authorization'));
        $this->assertNotContains('oauth_token=', $request->getHeader('Authorization'));
    }

    public function testRandomParametersAreNotAutomaticallyAdded()
    {
        $config = $this->config;
        $config['foo'] = 'bar';
        $s = new Oauth1($config);
        $client = new Client();
        $request = $client->createRequest('GET', 'http://httpbin.org', ['auth' => 'oauth']);
        $before = new BeforeEvent(new Transaction($client, $request));
        $s->onBefore($before);
        $this->assertTrue($request->hasHeader('Authorization'));
        $this->assertNotContains('foo=bar', $request->getHeader('Authorization'));
    }

    public function testAllowsRealm()
    {
        $config = $this->config;
        $config['realm'] = 'foo';
        $s = new Oauth1($config);
        $client = new Client();
        $request = $client->createRequest('GET', 'http://httpbin.org', ['auth' => 'oauth']);
        $before = new BeforeEvent(new Transaction($client, $request));
        $s->onBefore($before);
        $this->assertTrue($request->hasHeader('Authorization'));
        $this->assertContains('OAuth realm="foo",', $request->getHeader('Authorization'));
    }

    public function testTwitterIntegration()
    {
        if (empty($_SERVER['OAUTH_CONSUMER_SECRET'])) {
            $this->markTestSkipped('No OAUTH_CONSUMER_SECRET provided in phpunit.xml');
            return;
        }

        $client = new Client([
            'base_url' => 'https://api.twitter.com/1.1/',
            'defaults' => ['auth' => 'oauth']
        ]);

        $oauth = new Oauth1([
            'consumer_key'    => $_SERVER['OAUTH_CONSUMER_KEY'],
            'consumer_secret' => $_SERVER['OAUTH_CONSUMER_SECRET'],
            'token'           => $_SERVER['OAUTH_TOKEN'],
            'token_secret'    => $_SERVER['OAUTH_TOKEN_SECRET']
        ]);

        $client->getEmitter()->attach($oauth);

        try {
            $client->get('account/settings.json');
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
        if (empty($_SERVER['OAUTH_CONSUMER_SECRET'])) {
            $this->markTestSkipped('No OAUTH_CONSUMER_SECRET provided in phpunit.xml');
            return;
        }

        $client = new Client([
            'base_url' => 'https://stream.twitter.com/1.1/',
            'defaults' => ['auth' => 'oauth']
        ]);

        $oauth = new Oauth1([
            'consumer_key'    => $_SERVER['OAUTH_CONSUMER_KEY'],
            'consumer_secret' => $_SERVER['OAUTH_CONSUMER_SECRET'],
            'token'           => $_SERVER['OAUTH_TOKEN'],
            'token_secret'    => $_SERVER['OAUTH_TOKEN_SECRET']
        ]);

        $client->getEmitter()->attach($oauth);

        try {
            $response = $client->post('statuses/filter.json', [
                'body'   => ['track' => 'bieber'],
                'stream' => true
            ]);
            $body = $response->getBody();
            $data = $body::readLine($body);
            $this->assertContains('bieber', strtolower($data));
            $this->assertNotEmpty(json_decode($data, true));
            $body->close();
        } catch (ClientException $e) {
            if ($e->getResponse()->getStatusCode() == 429) {
                $this->markTestIncomplete('You are being throttled');
            } else {
                throw $e;
            }
        }
    }
}
