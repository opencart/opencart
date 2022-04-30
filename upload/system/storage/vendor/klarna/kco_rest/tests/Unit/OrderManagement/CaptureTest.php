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
 * File containing tests for the Capture class.
 */

namespace Klarna\Tests\Unit\Rest\OrderManagement;

use GuzzleHttp\Exception\RequestException;
use Klarna\Rest\OrderManagement\Capture;
use Klarna\Rest\Tests\Unit\TestCase;
use Klarna\Rest\Transport\Connector;
use Klarna\Rest\Transport\Exception\ConnectorException;

/**
 * Unit test cases for the capture resource.
 */
class CaptureTest extends TestCase
{
    /**
     * Make sure the location is correct for create method.
     *
     * @return void
     */
    public function testConstructorNew()
    {
        $capture = new Capture($this->connector, '/orders/1');
        $this->assertEquals('/orders/1/captures', $capture->getLocation());
    }

    /**
     * Make sure the location is correct for fetch method.
     *
     * @return void
     */
    public function testConstructorExisting()
    {
        $capture = new Capture($this->connector, '/orders/1', '2');
        $this->assertEquals('/orders/1/captures/2', $capture->getLocation());
    }

    /**
     * Make sure the identifier is retrievable.
     *
     * @return void
     */
    public function testGetId()
    {
        $capture = new Capture($this->connector, '/orders/12345');
        $this->assertNull($capture->getId());

        $capture = new Capture($this->connector, '/orders/12345', '2');
        $this->assertEquals('2', $capture->getId());
    }

    /**
     * Make sure fetched data is accessible.
     *
     * @return void
     */
    public function testFetch()
    {
        $this->connector->expects($this->once())
            ->method('createRequest')
            ->with(
                '/orders/1/captures/2',
                'GET',
                []
            )
            ->will($this->returnValue($this->request));

        $this->connector->expects($this->once())
            ->method('send')
            ->with($this->request)
            ->will($this->returnValue($this->response));

        $this->response->expects($this->once())
            ->method('getStatusCode')
            ->will($this->returnValue('200'));

        $this->response->expects($this->once())
            ->method('hasHeader')
            ->with('Content-Type')
            ->will($this->returnValue(true));

        $this->response->expects($this->once())
            ->method('getHeader')
            ->with('Content-Type')
            ->will($this->returnValue('application/json'));

        $data = [
            'data' => 'from response json',
            'capture_id' => '2'
        ];

        $this->response->expects($this->once())
            ->method('json')
            ->will($this->returnValue($data));

        $capture = new Capture($this->connector, '/orders/1', '2');
        $capture['data'] = 'is overwritten';

        $capture->fetch();

        $this->assertEquals('from response json', $capture['data']);
        $this->assertEquals('2', $capture->getId());
    }

    /**
     * Make sure that an unknown status code response results in an exception.
     *
     * @return void
     */
    public function testFetchInvalidStatusCode()
    {
        $this->connector->expects($this->once())
            ->method('createRequest')
            ->with(
                '/orders/1/captures/2',
                'GET',
                []
            )
            ->will($this->returnValue($this->request));

        $this->connector->expects($this->once())
            ->method('send')
            ->with($this->request)
            ->will($this->returnValue($this->response));

        $this->response->expects($this->once())
            ->method('getStatusCode')
            ->will($this->returnValue('204'));

        $capture = new Capture($this->connector, '/orders/1', '2');
        $capture['data'] = 'is overwritten';

        $this->setExpectedException(
            'RuntimeException',
            'Unexpected response status code: 204'
        );

        $capture->fetch();
    }

    /**
     * Make sure a non-JSON response results in an exception.
     *
     * @return void
     */
    public function testFetchNotJson()
    {
        $this->connector->expects($this->once())
            ->method('createRequest')
            ->with(
                '/orders/1/captures/2',
                'GET',
                []
            )
            ->will($this->returnValue($this->request));

        $this->connector->expects($this->once())
            ->method('send')
            ->with($this->request)
            ->will($this->returnValue($this->response));

        $this->response->expects($this->once())
            ->method('getStatusCode')
            ->will($this->returnValue('200'));

        $this->response->expects($this->once())
            ->method('hasHeader')
            ->with('Content-Type')
            ->will($this->returnValue(true));

        $this->response->expects($this->once())
            ->method('getHeader')
            ->with('Content-Type')
            ->will($this->returnValue('text/plain'));

        $capture = new Capture($this->connector, '/orders/1', '2');
        $capture['data'] = 'is overwritten';

        $this->setExpectedException(
            'RuntimeException',
            'Unexpected Content-Type header received: text/plain'
        );

        $capture->fetch();
    }

    /**
     * Make sure the correct data is sent and location is updated.
     *
     * @return void
     */
    public function testCreate()
    {
        $data = ['data' => 'goes here'];

        $this->connector->expects($this->once())
            ->method('createRequest')
            ->with(
                '/orders/1/captures',
                'POST',
                ['json' => $data]
            )
            ->will($this->returnValue($this->request));

        $this->connector->expects($this->once())
            ->method('send')
            ->with($this->request)
            ->will($this->returnValue($this->response));

        $this->response->expects($this->once())
            ->method('getStatusCode')
            ->will($this->returnValue('201'));

        $this->response->expects($this->once())
            ->method('hasHeader')
            ->with('Location')
            ->will($this->returnValue(true));

        $this->response->expects($this->once())
            ->method('getHeader')
            ->with('Location')
            ->will($this->returnValue('http://somewhere/a-path'));

        $capture = new Capture($this->connector, '/orders/1');
        $location = $capture->create($data)
            ->getLocation();

        $this->assertEquals('http://somewhere/a-path', $location);
    }

    /**
     * Make sure that an unknown status code response results in an exception.
     *
     * @return void
     */
    public function testCreateInvalidStatusCode()
    {
        $this->connector->expects($this->once())
            ->method('createRequest')
            ->will($this->returnValue($this->request));

        $this->connector->expects($this->once())
            ->method('send')
            ->with($this->request)
            ->will($this->returnValue($this->response));

        $this->response->expects($this->once())
            ->method('getStatusCode')
            ->will($this->returnValue('204'));

        $capture = new Capture($this->connector, '/orders/1');

        $this->setExpectedException(
            'RuntimeException',
            'Unexpected response status code: 204'
        );

        $capture->create(['data' => 'goes here']);
    }

    /**
     * Make sure a missing location header in the response results in an exception.
     *
     * @return void
     */
    public function testCreateNoLocation()
    {
        $this->connector->expects($this->once())
            ->method('createRequest')
            ->will($this->returnValue($this->request));

        $this->connector->expects($this->once())
            ->method('send')
            ->with($this->request)
            ->will($this->returnValue($this->response));

        $this->response->expects($this->once())
            ->method('getStatusCode')
            ->will($this->returnValue('201'));

        $this->response->expects($this->once())
            ->method('hasHeader')
            ->with('Location')
            ->will($this->returnValue(false));

        $capture = new Capture($this->connector, '/orders/1');

        $this->setExpectedException(
            'RuntimeException',
            'Response is missing a Location header'
        );

        $capture->create(['data' => 'goes here']);
    }

    /**
     * Make sure the correct data is sent.
     *
     * @return void
     */
    public function testAddShippingInfo()
    {
        $data = ['data' => 'goes here'];

        $this->connector->expects($this->once())
            ->method('createRequest')
            ->with(
                '/orders/1/captures/2/shipping-info',
                'POST',
                ['json' => $data]
            )
            ->will($this->returnValue($this->request));

        $this->connector->expects($this->once())
            ->method('send')
            ->with($this->request)
            ->will($this->returnValue($this->response));

        $this->response->expects($this->once())
            ->method('getStatusCode')
            ->will($this->returnValue('204'));

        $capture = new Capture($this->connector, '/orders/1', '2');
        $capture->addShippingInfo($data);
    }

    /**
     * Make sure an unknown status code response results in an exception.
     *
     * @return void
     */
    public function testAddShippingInfoInvalidStatusCode()
    {
        $data = ['data' => 'goes here'];

        $this->connector->expects($this->once())
            ->method('createRequest')
            ->with(
                '/orders/1/captures/2/shipping-info',
                'POST',
                ['json' => $data]
            )
            ->will($this->returnValue($this->request));

        $this->connector->expects($this->once())
            ->method('send')
            ->with($this->request)
            ->will($this->returnValue($this->response));

        $this->response->expects($this->once())
            ->method('getStatusCode')
            ->will($this->returnValue('200'));

        $capture = new Capture($this->connector, '/orders/1', '2');

        $this->setExpectedException(
            'RuntimeException',
            'Unexpected response status code: 200'
        );

        $capture->addShippingInfo($data);
    }

    /**
     * Make sure the correct data is sent.
     *
     * @return void
     */
    public function testUpdateCustomerDetails()
    {
        $data = ['data' => 'goes here'];

        $this->connector->expects($this->once())
            ->method('createRequest')
            ->with(
                '/orders/1/captures/2/customer-details',
                'PATCH',
                ['json' => $data]
            )
            ->will($this->returnValue($this->request));

        $this->connector->expects($this->once())
            ->method('send')
            ->with($this->request)
            ->will($this->returnValue($this->response));

        $this->response->expects($this->once())
            ->method('getStatusCode')
            ->will($this->returnValue('204'));

        $capture = new Capture($this->connector, '/orders/1', '2');
        $capture->updateCustomerDetails($data);
    }

    /**
     * Make sure an unknown status code response results in an exception.
     *
     * @return void
     */
    public function testUpdateCustomerDetailsInvalidStatusCode()
    {
        $data = ['data' => 'goes here'];

        $this->connector->expects($this->once())
            ->method('createRequest')
            ->with(
                '/orders/1/captures/2/customer-details',
                'PATCH',
                ['json' => $data]
            )
            ->will($this->returnValue($this->request));

        $this->connector->expects($this->once())
            ->method('send')
            ->with($this->request)
            ->will($this->returnValue($this->response));

        $this->response->expects($this->once())
            ->method('getStatusCode')
            ->will($this->returnValue('200'));

        $capture = new Capture($this->connector, '/orders/1', '2');

        $this->setExpectedException(
            'RuntimeException',
            'Unexpected response status code: 200'
        );

        $capture->updateCustomerDetails($data);
    }

    /**
     * Make sure that the correct request is sent.
     *
     * @return void
     */
    public function testTriggerSendout()
    {
        $this->connector->expects($this->once())
            ->method('createRequest')
            ->with(
                '/orders/1/captures/2/trigger-send-out',
                'POST',
                []
            )
            ->will($this->returnValue($this->request));

        $this->connector->expects($this->once())
            ->method('send')
            ->with($this->request)
            ->will($this->returnValue($this->response));

        $this->response->expects($this->once())
            ->method('getStatusCode')
            ->will($this->returnValue('204'));

        $capture = new Capture($this->connector, '/orders/1', '2');
        $capture->triggerSendout();
    }

    /**
     * Make sure an unknown status code response results in an exception.
     *
     * @return void
     */
    public function testTriggerSendoutInvalidStatusCode()
    {
        $this->connector->expects($this->once())
            ->method('createRequest')
            ->with(
                '/orders/1/captures/2/trigger-send-out',
                'POST',
                []
            )
            ->will($this->returnValue($this->request));

        $this->connector->expects($this->once())
            ->method('send')
            ->with($this->request)
            ->will($this->returnValue($this->response));

        $this->response->expects($this->once())
            ->method('getStatusCode')
            ->will($this->returnValue('200'));

        $capture = new Capture($this->connector, '/orders/1', '2');

        $this->setExpectedException(
            'RuntimeException',
            'Unexpected response status code: 200'
        );

        $capture->triggerSendout();
    }
}
