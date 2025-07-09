<?php

namespace Cardinity\Tests;

use Cardinity\Client;

class ClientEndpointTest extends ClientTestCase
{
    private $baseConfig;
    private $log;

    /**
     * @return void
     */
    public function setUp(): void
    {
        $this->baseConfig = [
            'consumerKey' => $_ENV['CONSUMER_KEY'],
            'consumerSecret' => $_ENV['CONSUMER_SECRET'],
        ];
        $this->log = Client::LOG_NONE;
    }

    /**
     * Test create client with invalid URL
     *
     * @return void
     */
    public function testInvalidURLEndpoint(){

        $this->baseConfig['apiEndpoint'] = '123abc';
        try {
            Client::create($this->baseConfig, $this->log);
        } catch (\Exception $e) {
            $this->assertStringContainsString('Your API endpoint is not a valid URL', $e->getMessage());
        }
    }

     /**
     * Test create client with valid URL,
     *
     * @return void
     */
    public function testValidUrlWrongEndpoint(){

        $this->baseConfig['apiEndpoint'] = 'https://example.com/';

        try {
            $client = Client::create($this->baseConfig, $this->log);
        } catch (\Exception $e) {
            $this->assertStringContainsString('error', $e->getMessage());
        }
        $this->assertInstanceOf('Cardinity\Client', $client);
    }

    /**
     * Test create client with valid URL,
     *
     * @return void
     */
    public function testValidURLValidEndpoint(){

        $this->baseConfig['apiEndpoint'] = 'https://api.cardinity.com/v1/';
        $client = Client::create($this->baseConfig, $this->log);
        $this->assertInstanceOf('Cardinity\Client', $client);
    }

     /**
     * Test create client with valid URL,
     *
     * @return void
     */
    public function testNoEndpoint(){

        unset($this->baseConfig['apiEndpoint']);
        $client = Client::create($this->baseConfig, $this->log);

        $reflection = new \ReflectionObject($client);
        $urlProperty = $reflection->getProperty('url');
        $urlProperty->setAccessible(true);

        $this->assertSame($urlProperty->getValue(),'https://api.cardinity.com/v1/');
        $this->assertInstanceOf('Cardinity\Client', $client);
    }
}
