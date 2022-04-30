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
 * File containing tests for the Order class.
 */

namespace Klarna\Tests\Unit\Rest\Checkout;

use GuzzleHttp\Exception\RequestException;
use Klarna\Rest\Checkout\Order;
use Klarna\Rest\Tests\Unit\TestCase;
use Klarna\Rest\Transport\Connector;
use Klarna\Rest\Transport\Exception\ConnectorException;

/**
 * Unit test cases for the checkout order resource.
 */
class OrderTest extends TestCase
{
    /**
     * Make sure the identifier is retrievable.
     *
     * @return void
     */
    public function testGetId()
    {
        $order = new Order($this->connector);
        $this->assertNull($order->getId());

        $order = new Order($this->connector, '12345');
        $order['order_id'] = '12345';
        $this->assertEquals('12345', $order->getId());
        $this->assertEquals('/checkout/v3/orders/12345', $order->getLocation());
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
                '/checkout/v3/orders',
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

        $order = new Order($this->connector);
        $location = $order->create($data)
            ->getLocation();

        $this->assertEquals('http://somewhere/a-path', $location);
    }

    /**
     * Make sure an unknown status code response results in an exception.
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

        $order = new Order($this->connector);

        $this->setExpectedException(
            'RuntimeException',
            'Unexpected response status code: 204'
        );

        $order->create(['data' => 'goes here']);
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

        $order = new Order($this->connector);

        $this->setExpectedException(
            'RuntimeException',
            'Response is missing a Location header'
        );

        $order->create(['data' => 'goes here']);
    }

    /**
     * Make sure the correct data is sent and that the replied data is accessible.
     *
     * @return void
     */
    public function testUpdate()
    {
        $updateData = ['data' => 'goes here'];

        $this->connector->expects($this->once())
            ->method('createRequest')
            ->with(
                '/checkout/v3/orders/12345',
                'POST',
                ['json' => $updateData]
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
            'order_id' => '12345'
        ];

        $this->response->expects($this->once())
            ->method('json')
            ->will($this->returnValue($data));

        $order = new Order($this->connector, '12345');
        $order['order_id'] = '12345';
        $order['data'] = 'is overwritten';

        $order->update($updateData);

        $this->assertEquals('from response json', $order['data']);
        $this->assertEquals('12345', $order->getId());
    }

    /**
     * Make sure an unknown status code response results in an exception.
     *
     * @return void
     */
    public function testUpdateInvalidStatusCode()
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

        $order = new Order($this->connector);

        $this->setExpectedException(
            'RuntimeException',
            'Unexpected response status code: 204'
        );

        $order->update(['data' => 'goes here']);
    }

    /**
     * Make sure a non-JSON response results in an exception.
     *
     * @return void
     */
    public function testUpdateNotJson()
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
            ->will($this->returnValue('200'));

        $this->response->expects($this->once())
            ->method('hasHeader')
            ->with('Content-Type')
            ->will($this->returnValue(true));

        $this->response->expects($this->once())
            ->method('getHeader')
            ->with('Content-Type')
            ->will($this->returnValue('text/plain'));

        $order = new Order($this->connector);

        $this->setExpectedException(
            'RuntimeException',
            'Unexpected Content-Type header received: text/plain'
        );

        $order->update(['data' => 'goes here']);
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
                '/checkout/v3/orders/12345',
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
            'order_id' => '12345'
        ];

        $this->response->expects($this->once())
            ->method('json')
            ->will($this->returnValue($data));

        $order = new Order($this->connector, '12345');
        $order['data'] = 'is overwritten';

        $order->fetch();

        $this->assertEquals('from response json', $order['data']);
        $this->assertEquals('12345', $order->getId());
    }

    /**
     * Make sure an unknown status code response results in an exception.
     *
     * @return void
     */
    public function testFetchInvalidStatusCode()
    {
        $this->connector->expects($this->once())
            ->method('createRequest')
            ->with(
                '/checkout/v3/orders/12345',
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

        $order = new Order($this->connector, '12345');
        $order['data'] = 'is overwritten';

        $this->setExpectedException(
            'RuntimeException',
            'Unexpected response status code: 204'
        );

        $order->fetch();
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
                '/checkout/v3/orders/12345',
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

        $order = new Order($this->connector, '12345');
        $order['data'] = 'is overwritten';

        $this->setExpectedException(
            'RuntimeException',
            'Unexpected Content-Type header received: text/plain'
        );

        $order->fetch();
    }
}
