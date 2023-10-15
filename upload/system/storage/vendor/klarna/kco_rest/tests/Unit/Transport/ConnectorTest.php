<?php
/**
 * Copyright 2014 Klarna AB
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 *
 * File containing tests for the Connector class.
 */

namespace Klarna\Rest\Tests\Unit\Transport;

use GuzzleHttp\Exception\RequestException;
use Klarna\Rest\Tests\Unit\TestCase;
use Klarna\Rest\Transport\Connector;
use Klarna\Rest\Transport\Exception\ConnectorException;

/**
 * Unit test cases for the connector class.
 */
class ConnectorTest extends TestCase
{
    const MERCHANT_ID = '1234';

    const SHARED_SECRET = 'MySecret';

    const BASE_URL = 'http://base-url.internal.machines';

    const PATH = '/test/url';

    /**
     * @var Connector
     */
    protected $object;

    /**
     * @var \GuzzleHttp\ClientInterface
     */
    protected $client;

    /**
     * @var \Klarna\Rest\Transport\UserAgentInterface
     */
    protected $userAgent;

    /**
     * Set up the test fixtures.
     */
    protected function setUp()
    {
        parent::setUp();

        $this->client = $this->getMockBuilder('GuzzleHttp\ClientInterface')
            ->getMock();

        $this->userAgent = $this->getMockBuilder('Klarna\Rest\Transport\UserAgent')
            ->getMock();

        $this->userAgent->expects($this->any())
            ->method('__toString')
            ->will($this->returnValue('a-user-agent'));

        $this->object = new Connector(
            $this->client,
            self::MERCHANT_ID,
            self::SHARED_SECRET,
            $this->userAgent
        );
    }

    /**
     * Make sure that the request is created as intended.
     *
     * @return void
     */
    public function testCreateRequest()
    {
        $options = [
            'opt' => 'val',
            'auth' => [self::MERCHANT_ID, self::SHARED_SECRET],
            'headers' => ['User-Agent' => 'a-user-agent']
        ];

        $this->client->expects($this->any())
            ->method('createRequest')
            ->with('uri', 'method', $options)
            ->will($this->returnValue($this->request));

        $request = $this->object->createRequest('method', 'uri', ['opt' => 'val']);
        $this->assertSame($this->request, $request);
    }

    /**
     * Make sure that the request is sent and a response is returned.
     *
     * @return void
     */
    public function testSend()
    {
        $this->client->expects($this->once())
            ->method('send')
            ->with($this->request)
            ->will($this->returnValue($this->response));

        $response = $this->object->send($this->request);

        $this->assertSame($this->response, $response);
    }

    /**
     * Make sure that an exception without a response is re-thrown.
     *
     * @return void
     */
    public function testSendRequestException()
    {
        $exception = new RequestException(
            'Something went terribly wrong',
            $this->request
        );

        $this->client->expects($this->once())
            ->method('send')
            ->with($this->request)
            ->will($this->throwException($exception));

        $this->setExpectedException(
            'GuzzleHttp\Exception\RequestException',
            'Something went terribly wrong'
        );

        $this->object->send($this->request);
    }

    /**
     * Make sure that an exception without a JSON response is re-thrown.
     *
     * @return void
     */
    public function testSendConnectorExceptionNoJson()
    {
        $this->response->expects($this->once())
            ->method('getHeader')
            ->with('Content-Type')
            ->will($this->returnValue(''));

        $exception = new RequestException(
            'Something went terribly wrong',
            $this->request,
            $this->response
        );

        $this->client->expects($this->once())
            ->method('send')
            ->with($this->request)
            ->will($this->throwException($exception));

        $this->setExpectedException(
            'GuzzleHttp\Exception\RequestException',
            'Something went terribly wrong'
        );

        $this->object->send($this->request);
    }

    /**
     * Make sure that an exception without data but with json content-type is
     * re-thrown.
     *
     * @return void
     */
    public function testSendConnectorExceptionEmptyJson()
    {
        $this->response->expects($this->once())
            ->method('getHeader')
            ->with('Content-Type')
            ->will($this->returnValue('application/json'));

        $exception = new RequestException(
            'Something went terribly wrong',
            $this->request,
            $this->response
        );

        $this->client->expects($this->once())
            ->method('send')
            ->with($this->request)
            ->will($this->throwException($exception));

        $this->setExpectedException(
            'GuzzleHttp\Exception\RequestException',
            'Something went terribly wrong'
        );

        $this->object->send($this->request);
    }

    /**
     * Make sure that an exception without a proper JSON response is re-thrown.
     *
     * @return void
     */
    public function testSendConnectorExceptionMissingFields()
    {
        $this->response->expects($this->once())
            ->method('getHeader')
            ->with('Content-Type')
            ->will($this->returnValue('application/json'));

        $data = [];

        $this->response->expects($this->once())
            ->method('json')
            ->will($this->returnValue($data));

        $exception = new RequestException(
            'Something went terribly wrong',
            $this->request,
            $this->response
        );

        $this->client->expects($this->once())
            ->method('send')
            ->with($this->request)
            ->will($this->throwException($exception));

        $this->setExpectedException(
            'GuzzleHttp\Exception\RequestException',
            'Something went terribly wrong'
        );

        $this->object->send($this->request);
    }

    /**
     * Make sure that an exception with a error response is wrapped properly.
     *
     * @return void
     */
    public function testSendConnectorException()
    {
        $this->response->expects($this->once())
            ->method('getHeader')
            ->with('Content-Type')
            ->will($this->returnValue('application/json'));

        $data = [
            'error_code' => 'ERROR_CODE_1',
            'error_messages' => [
                'Oh dear...',
                'Oh no...'
            ],
            'correlation_id' => 'corr_id_1'
        ];

        $this->response->expects($this->once())
            ->method('json')
            ->will($this->returnValue($data));

        $exception = new RequestException(
            'Something went terribly wrong',
            $this->request,
            $this->response
        );

        $this->client->expects($this->once())
            ->method('send')
            ->with($this->request)
            ->will($this->throwException($exception));

        $this->setExpectedException(
            'Klarna\Rest\Transport\Exception\ConnectorException',
            'ERROR_CODE_1: Oh dear..., Oh no... (#corr_id_1)'
        );

        $this->object->send($this->request);
    }

    /**
     * Make sure that the factory method creates a connector properly.
     *
     * @return void
     */
    public function testCreate()
    {
        $connector = Connector::create(
            self::MERCHANT_ID,
            self::SHARED_SECRET,
            self::BASE_URL,
            $this->userAgent
        );

        $client = $connector->getClient();
        $this->assertInstanceOf('GuzzleHttp\ClientInterface', $client);

        $this->assertEquals(self::BASE_URL, $client->getBaseUrl());

        $userAgent = $connector->getUserAgent();

        $this->assertSame($this->userAgent, $userAgent);
        $this->assertEquals('a-user-agent', strval($userAgent));
    }

    /**
     * Make sure that the factory method uses the default user agent.
     *
     * @return void
     */
    public function testCreateDefaultUserAgent()
    {
        $connector = Connector::create(
            self::MERCHANT_ID,
            self::SHARED_SECRET,
            self::BASE_URL
        );

        $userAgent = $connector->getUserAgent();
        $this->assertInstanceOf('Klarna\Rest\Transport\UserAgent', $userAgent);
        $this->assertContains('Library/Klarna.kco_rest_php', strval($userAgent));
    }

    /**
     * Make sure that the client is retrievable.
     *
     * @return void
     */
    public function testGetClient()
    {
        $client = $this->object->getClient();

        $this->assertInstanceOf('GuzzleHttp\ClientInterface', $client);
        $this->assertSame($this->client, $client);
    }
}
