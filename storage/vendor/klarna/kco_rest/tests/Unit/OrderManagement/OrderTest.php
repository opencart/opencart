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

namespace Klarna\Tests\Unit\Rest\OrderManagement;

use GuzzleHttp\Exception\RequestException;
use Klarna\Rest\OrderManagement\Order;
use Klarna\Rest\Tests\Unit\TestCase;
use Klarna\Rest\Transport\Connector;
use Klarna\Rest\Transport\Exception\ConnectorException;

/**
 * Unit test cases for the order resource.
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
        $order = new Order($this->connector, '12345');
        $this->assertEquals('12345', $order->getId());
        $this->assertEquals('/ordermanagement/v1/orders/12345', $order->getLocation());
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
                '/ordermanagement/v1/orders/12345',
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
            'order_id' => '12345',
            'captures' => [
                [
                    'capture_id' => '1002',
                    'data' => 'also from json'
                ],
                [
                    'capture_id' => '1003',
                    'data' => 'something else'
                ]
            ]
        ];

        $this->response->expects($this->once())
            ->method('json')
            ->will($this->returnValue($data));

        $order = new Order($this->connector, '12345');
        $order['data'] = 'is overwritten';
        $order['captures'][] = new \ArrayObject();

        $order->fetch();

        $this->assertEquals('from response json', $order['data']);
        $this->assertEquals('12345', $order->getId());

        $this->assertCount(2, $order['captures']);
        $this->assertContainsOnlyInstancesOf(
            'Klarna\Rest\OrderManagement\Capture',
            $order['captures']
        );

        $this->assertEquals('1002', $order['captures'][0]->getId());
        $this->assertEquals('also from json', $order['captures'][0]['data']);
        $this->assertEquals('1003', $order['captures'][1]->getId());
        $this->assertEquals('something else', $order['captures'][1]['data']);
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
                '/ordermanagement/v1/orders/12345',
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
                '/ordermanagement/v1/orders/12345',
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

    /**
     * Make sure that the correct request is sent.
     *
     * @return void
     */
    public function testAcknowledge()
    {
        $this->connector->expects($this->once())
            ->method('createRequest')
            ->with(
                '/ordermanagement/v1/orders/12345/acknowledge',
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

        $order = new Order($this->connector, '12345');
        $order->acknowledge();
    }

    /**
     * Make sure an unknown status code response results in an exception.
     *
     * @return void
     */
    public function testAcknowledgeInvalidStatusCode()
    {
        $this->connector->expects($this->once())
            ->method('createRequest')
            ->with(
                '/ordermanagement/v1/orders/12345/acknowledge',
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

        $order = new Order($this->connector, '12345');

        $this->setExpectedException(
            'RuntimeException',
            'Unexpected response status code: 200'
        );

        $order->acknowledge();
    }

    /**
     * Make sure that the correct request is sent.
     *
     * @return void
     */
    public function testCancel()
    {
        $this->connector->expects($this->once())
            ->method('createRequest')
            ->with(
                '/ordermanagement/v1/orders/12345/cancel',
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

        $order = new Order($this->connector, '12345');
        $order->cancel();
    }

    /**
     * Make sure an unknown status code response results in an exception.
     *
     * @return void
     */
    public function testCancelInvalidStatusCode()
    {
        $this->connector->expects($this->once())
            ->method('createRequest')
            ->with(
                '/ordermanagement/v1/orders/12345/cancel',
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

        $order = new Order($this->connector, '12345');

        $this->setExpectedException(
            'RuntimeException',
            'Unexpected response status code: 200'
        );

        $order->cancel();
    }

    /**
     * Make sure the correct data is sent.
     *
     * @return void
     */
    public function testUpdateAuthorization()
    {
        $data = ['data' => 'goes here'];

        $this->connector->expects($this->once())
            ->method('createRequest')
            ->with(
                '/ordermanagement/v1/orders/12345/authorization',
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

        $order = new Order($this->connector, '12345');
        $order->updateAuthorization($data);
    }

    /**
     * Make sure an unknown status code response results in an exception.
     *
     * @return void
     */
    public function testUpdateAuthorizationInvalidStatusCode()
    {
        $data = ['data' => 'goes here'];

        $this->connector->expects($this->once())
            ->method('createRequest')
            ->with(
                '/ordermanagement/v1/orders/12345/authorization',
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

        $order = new Order($this->connector, '12345');

        $this->setExpectedException(
            'RuntimeException',
            'Unexpected response status code: 200'
        );

        $order->updateAuthorization($data);
    }

    /**
     * Make sure that the correct request is sent.
     *
     * @return void
     */
    public function testExtendAuthorizationTime()
    {
        $this->connector->expects($this->once())
            ->method('createRequest')
            ->with(
                '/ordermanagement/v1/orders/12345/extend-authorization-time',
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

        $order = new Order($this->connector, '12345');
        $order->extendAuthorizationTime();
    }

    /**
     * Make sure an unknown status code response results in an exception.
     *
     * @return void
     */
    public function testExtendAuthorizationTimeInvalidStatusCode()
    {
        $this->connector->expects($this->once())
            ->method('createRequest')
            ->with(
                '/ordermanagement/v1/orders/12345/extend-authorization-time',
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

        $order = new Order($this->connector, '12345');

        $this->setExpectedException(
            'RuntimeException',
            'Unexpected response status code: 200'
        );

        $order->extendAuthorizationTime();
    }

    /**
     * Make sure the correct data is sent.
     *
     * @return void
     */
    public function testUpdateMerchantReferences()
    {
        $data = ['data' => 'goes here'];

        $this->connector->expects($this->once())
            ->method('createRequest')
            ->with(
                '/ordermanagement/v1/orders/12345/merchant-references',
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

        $order = new Order($this->connector, '12345');
        $order->updateMerchantReferences($data);
    }

    /**
     * Make sure an unknown status code response results in an exception.
     *
     * @return void
     */
    public function testUpdateMerchantReferencesInvalidStatusCode()
    {
        $data = ['data' => 'goes here'];

        $this->connector->expects($this->once())
            ->method('createRequest')
            ->with(
                '/ordermanagement/v1/orders/12345/merchant-references',
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

        $order = new Order($this->connector, '12345');

        $this->setExpectedException(
            'RuntimeException',
            'Unexpected response status code: 200'
        );

        $order->updateMerchantReferences($data);
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
                '/ordermanagement/v1/orders/12345/customer-details',
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

        $order = new Order($this->connector, '12345');
        $order->updateCustomerDetails($data);
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
                '/ordermanagement/v1/orders/12345/customer-details',
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

        $order = new Order($this->connector, '12345');

        $this->setExpectedException(
            'RuntimeException',
            'Unexpected response status code: 200'
        );

        $order->updateCustomerDetails($data);
    }

    /**
     * Make sure the correct data is sent.
     *
     * @return void
     */
    public function testRefund()
    {
        $data = ['data' => 'goes here'];

        $this->connector->expects($this->once())
            ->method('createRequest')
            ->with(
                '/ordermanagement/v1/orders/12345/refunds',
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

        $order = new Order($this->connector, '12345');
        $order->refund($data);
    }

    /**
     * Make sure an unknown status code response results in an exception.
     *
     * @return void
     */
    public function testRefundInvalidStatusCode()
    {
        $data = ['data' => 'goes here'];

        $this->connector->expects($this->once())
            ->method('createRequest')
            ->with(
                '/ordermanagement/v1/orders/12345/refunds',
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

        $order = new Order($this->connector, '12345');

        $this->setExpectedException(
            'RuntimeException',
            'Unexpected response status code: 200'
        );

        $order->refund($data);
    }

    /**
     * Make sure that the correct request is sent.
     *
     * @return void
     */
    public function testReleaseRemainingAuthorization()
    {
        $this->connector->expects($this->once())
            ->method('createRequest')
            ->with(
                '/ordermanagement/v1/orders/12345/release-remaining-authorization',
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

        $order = new Order($this->connector, '12345');
        $order->releaseRemainingAuthorization();
    }

    /**
     * Make sure an unknown status code response results in an exception.
     *
     * @return void
     */
    public function testReleaseRemainingAuthorizationInvalidStatusCode()
    {
        $this->connector->expects($this->once())
            ->method('createRequest')
            ->with(
                '/ordermanagement/v1/orders/12345/release-remaining-authorization',
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

        $order = new Order($this->connector, '12345');

        $this->setExpectedException(
            'RuntimeException',
            'Unexpected response status code: 200'
        );

        $order->releaseRemainingAuthorization();
    }

    /**
     * Make sure that a capture is created properly.
     *
     * @return void
     */
    public function testCreateCapture()
    {
        $data = ['data' => 'goes here'];

        $this->connector->expects($this->once())
            ->method('createRequest')
            ->with(
                '/ordermanagement/v1/orders/12345/captures',
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

        $order = new Order($this->connector, '12345');
        $capture = $order->createCapture($data);

        $this->assertEquals('http://somewhere/a-path', $capture->getLocation());
    }

    /**
     * Make sure that a capture is fetched.
     *
     * @return void
     */
    public function testFetchCapture()
    {
        $this->connector->expects($this->once())
            ->method('createRequest')
            ->with(
                '/ordermanagement/v1/orders/12345/captures/2',
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

        $order = new Order($this->connector, '12345');
        $capture = $order->fetchCapture('2');

        $this->assertInstanceOf('Klarna\Rest\OrderManagement\Capture', $capture);
        $this->assertEquals('from response json', $capture['data']);
    }

    /**
     * Make sure that an existing capture is refreshed before returned.
     *
     * @return void
     */
    public function testFetchCaptureExisting()
    {
        $this->connector->expects($this->never())
            ->method('createRequest');

        $order = new Order($this->connector, '12345');

        $order['captures'][] = $this->getMockBuilder('Klarna\Rest\OrderManagement\Capture')
            ->disableOriginalConstructor()
            ->getMock();

        $order['captures'][0]->expects($this->once())
            ->method('getId')
            ->will($this->returnValue('1'));

        $order['captures'][0]->expects($this->never())
            ->method('fetch');

        $order['captures'][] = $this->getMockBuilder('Klarna\Rest\OrderManagement\Capture')
            ->disableOriginalConstructor()
            ->getMock();

        $order['captures'][1]->expects($this->once())
            ->method('getId')
            ->will($this->returnValue('2'));

        $order['captures'][1]->expects($this->once())
            ->method('fetch')
            ->will($this->returnValue($order['captures'][1]));

        $this->assertSame($order['captures'][1], $order->fetchCapture('2'));
    }

    /**
     * Make sure that a new capture is fetched if it is not in the resource.
     *
     * @return void
     */
    public function testFetchCaptureNoCache()
    {
        $this->connector->expects($this->once())
            ->method('createRequest')
            ->with(
                '/ordermanagement/v1/orders/12345/captures/2',
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

        $order = new Order($this->connector, '12345');
        $order['captures'][] = $this->getMockBuilder('Klarna\Rest\OrderManagement\Capture')
            ->disableOriginalConstructor()
            ->getMock();

        $order['captures'][0]->expects($this->once())
            ->method('getId')
            ->will($this->returnValue('1'));

        $capture = $order->fetchCapture('2');

        $this->assertInstanceOf('Klarna\Rest\OrderManagement\Capture', $capture);
        $this->assertEquals('from response json', $capture['data']);
        $this->assertEquals('2', $capture->getId());
    }
}
