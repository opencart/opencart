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

namespace Klarna\Rest\Tests\Component\OrderManagement;

use GuzzleHttp\Message\Response;
use GuzzleHttp\Stream\Stream;
use Klarna\Rest\OrderManagement\Capture;
use Klarna\Rest\OrderManagement\Order;
use Klarna\Rest\Tests\Component\ResourceTestCase;
use Klarna\Rest\Transport\Connector;

/**
 * Component test cases for the order resource.
 */
class OrderTest extends ResourceTestCase
{
    /**
     * Make sure that the request sent and retrieved data is correct when fetching
     * the order.
     *
     * @return void
     */
    public function testFetch()
    {
        $json = <<<JSON
{
    "order_id": "0002",
    "updated": "from json",
    "captures": [
        {
            "capture_id": "1002",
            "test": "data"
        }
    ]
}
JSON;

        $this->mock->addResponse(
            new Response(
                200,
                ['Content-Type' => 'application/json'],
                Stream::factory($json)
            )
        );

        $order = new Order($this->connector, '0002');
        $order['updated'] = 'not from json';

        $order->fetch();

        $this->assertEquals('from json', $order['updated']);
        $this->assertEquals('0002', $order->getId());

        $request = $this->history->getLastRequest();
        $this->assertEquals('GET', $request->getMethod());
        $this->assertEquals('/ordermanagement/v1/orders/0002', $request->getPath());

        $this->assertAuthorization($request);

        $capture = $order['captures'][0];
        $this->assertInstanceOf('Klarna\Rest\OrderManagement\Capture', $capture);
        $this->assertEquals($capture->getId(), $capture['capture_id']);
        $this->assertEquals('1002', $capture->getId());
        $this->assertEquals('data', $capture['test']);
    }

    /**
     * Make sure that the request sent is correct when acknowledging an order.
     *
     * @return void
     */
    public function testAcknowledge()
    {
        $this->mock->addResponse(new Response(204));

        $order = new Order($this->connector, '0002');
        $order->acknowledge();

        $request = $this->history->getLastRequest();
        $this->assertEquals('POST', $request->getMethod());
        $this->assertEquals(
            '/ordermanagement/v1/orders/0002/acknowledge',
            $request->getPath()
        );

        $this->assertAuthorization($request);
    }

    /**
     * Make sure that the request sent is correct when cancelling an order.
     *
     * @return void
     */
    public function testCancel()
    {
        $this->mock->addResponse(new Response(204));

        $order = new Order($this->connector, '0002');
        $order->cancel();

        $request = $this->history->getLastRequest();
        $this->assertEquals('POST', $request->getMethod());
        $this->assertEquals(
            '/ordermanagement/v1/orders/0002/cancel',
            $request->getPath()
        );

        $this->assertAuthorization($request);
    }

    /**
     * Make sure that the request sent is correct when extending authorization time.
     *
     * @return void
     */
    public function testExtendAuthorizationTime()
    {
        $this->mock->addResponse(new Response(204));

        $order = new Order($this->connector, '0002');
        $order->extendAuthorizationTime();

        $request = $this->history->getLastRequest();
        $this->assertEquals('POST', $request->getMethod());
        $this->assertEquals(
            '/ordermanagement/v1/orders/0002/extend-authorization-time',
            $request->getPath()
        );

        $this->assertAuthorization($request);
    }

    /**
     * Make sure that the request sent is correct when releasing remaining
     * authorization.
     *
     * @return void
     */
    public function testReleaseRemainingAuthorization()
    {
        $this->mock->addResponse(new Response(204));

        $order = new Order($this->connector, '0002');
        $order->releaseRemainingAuthorization();

        $request = $this->history->getLastRequest();
        $this->assertEquals('POST', $request->getMethod());
        $this->assertEquals(
            '/ordermanagement/v1/orders/0002/release-remaining-authorization',
            $request->getPath()
        );

        $this->assertAuthorization($request);
    }

    /**
     * Make sure that the request sent is correct when updating authorization.
     *
     * @return void
     */
    public function testUpdateAuthorization()
    {
        $this->mock->addResponse(new Response(204));

        $order = new Order($this->connector, '0002');
        $order->updateAuthorization(['data' => 'sent in']);

        $request = $this->history->getLastRequest();
        $this->assertEquals('PATCH', $request->getMethod());
        $this->assertEquals(
            '/ordermanagement/v1/orders/0002/authorization',
            $request->getPath()
        );

        $this->assertEquals('application/json', $request->getHeader('Content-Type'));
        $this->assertEquals('{"data":"sent in"}', strval($request->getBody()));

        $this->assertAuthorization($request);
    }

    /**
     * Make sure that the request sent is correct when updating merchant references.
     *
     * @return void
     */
    public function testUpdateMerchantReferences()
    {
        $this->mock->addResponse(new Response(204));

        $order = new Order($this->connector, '0002');
        $order->updateMerchantReferences(['data' => 'sent in']);

        $request = $this->history->getLastRequest();
        $this->assertEquals('PATCH', $request->getMethod());
        $this->assertEquals(
            '/ordermanagement/v1/orders/0002/merchant-references',
            $request->getPath()
        );

        $this->assertEquals('application/json', $request->getHeader('Content-Type'));
        $this->assertEquals('{"data":"sent in"}', strval($request->getBody()));

        $this->assertAuthorization($request);
    }

    /**
     * Make sure that the request sent is correct when updating customer details.
     *
     * @return void
     */
    public function testUpdateCustomerDetails()
    {
        $this->mock->addResponse(new Response(204));

        $order = new Order($this->connector, '0002');
        $order->updateCustomerDetails(['data' => 'sent in']);

        $request = $this->history->getLastRequest();
        $this->assertEquals('PATCH', $request->getMethod());
        $this->assertEquals(
            '/ordermanagement/v1/orders/0002/customer-details',
            $request->getPath()
        );

        $this->assertEquals('application/json', $request->getHeader('Content-Type'));
        $this->assertEquals('{"data":"sent in"}', strval($request->getBody()));

        $this->assertAuthorization($request);
    }

    /**
     * Make sure that the request sent is correct when performing a refund.
     *
     * @return void
     */
    public function testRefund()
    {
        $this->mock->addResponse(new Response(204));

        $order = new Order($this->connector, '0002');
        $order->refund(['data' => 'sent in']);

        $request = $this->history->getLastRequest();
        $this->assertEquals('POST', $request->getMethod());
        $this->assertEquals(
            '/ordermanagement/v1/orders/0002/refunds',
            $request->getPath()
        );

        $this->assertEquals('application/json', $request->getHeader('Content-Type'));
        $this->assertEquals('{"data":"sent in"}', strval($request->getBody()));

        $this->assertAuthorization($request);
    }

    /**
     * Make sure that the request sent is correct when performing a refund.
     *
     * @return void
     */
    public function testRefund201()
    {
        $this->mock->addResponse(new Response(201));

        $order = new Order($this->connector, '0002');
        $order->refund(['data' => 'sent in']);

        $request = $this->history->getLastRequest();
        $this->assertEquals('POST', $request->getMethod());
        $this->assertEquals(
            '/ordermanagement/v1/orders/0002/refunds',
            $request->getPath()
        );

        $this->assertEquals('application/json', $request->getHeader('Content-Type'));
        $this->assertEquals('{"data":"sent in"}', strval($request->getBody()));

        $this->assertAuthorization($request);
    }

    /**
     * Make sure that the request sent is correct and that the location is updated
     * when creating an order.
     *
     * @return void
     */
    public function testCreateCapture()
    {
        $this->mock->addResponse(
            new Response(201, ['Location' => 'http://somewhere/a-path'])
        );

        $order = new Order($this->connector, '0002');
        $capture = $order->createCapture(['data' => 'goes here']);

        $this->assertInstanceOf('Klarna\Rest\OrderManagement\Capture', $capture);
        $this->assertEquals('http://somewhere/a-path', $capture->getLocation());

        $request = $this->history->getLastRequest();
        $this->assertEquals('POST', $request->getMethod());
        $this->assertEquals(
            '/ordermanagement/v1/orders/0002/captures',
            $request->getPath()
        );

        $this->assertEquals('application/json', $request->getHeader('Content-Type'));
        $this->assertEquals('{"data":"goes here"}', strval($request->getBody()));

        $this->assertAuthorization($request);
    }

    /**
     * Make sure that the request sent and retrieved data is correct when fetching
     * a capture.
     *
     * @return void
     */
    public function testFetchCapture()
    {
        $json = <<<JSON
{
    "capture_id": "1002",
    "updated": "from json"
}
JSON;

        $this->mock->addResponse(
            new Response(
                200,
                ['Content-Type' => 'application/json'],
                Stream::factory($json)
            )
        );

        $order = new Order($this->connector, '0002');

        $capture = $order->fetchCapture('1002');
        $this->assertInstanceOf('Klarna\Rest\OrderManagement\Capture', $capture);
        $this->assertEquals(
            '/ordermanagement/v1/orders/0002/captures/1002',
            $capture->getLocation()
        );

        $this->assertEquals('from json', $capture['updated']);
        $this->assertEquals('1002', $capture->getId());
        $this->assertEquals($capture->getId(), $capture['capture_id']);
    }

    /**
     * Make sure that the request sent and retrieved data is correct when fetching
     * a capture that exists in the captures list.
     *
     * @return void
     */
    public function testFetchCaptureExisting()
    {
        $json = <<<JSON
{
    "capture_id": "1002",
    "updated": "from json"
}
JSON;

        $this->mock->addResponse(
            new Response(
                200,
                ['Content-Type' => 'application/json'],
                Stream::factory($json)
            )
        );

        $order = new Order($this->connector, '0002');

        $capture = new Capture($this->connector, $order->getLocation(), '1002');
        $capture['capture_id'] = '1002';
        $capture['updated'] = 'not from json';

        $order['captures'][] = $capture;

        $capture = $order->fetchCapture('1002');
        $this->assertInstanceOf('Klarna\Rest\OrderManagement\Capture', $capture);
        $this->assertEquals(
            '/ordermanagement/v1/orders/0002/captures/1002',
            $capture->getLocation()
        );

        $this->assertEquals('from json', $capture['updated']);
        $this->assertEquals('1002', $capture->getId());
        $this->assertEquals($capture->getId(), $capture['capture_id']);
    }

    /**
     * Make sure that the request sent and retrieved data is correct when fetching
     * a capture that is not already in the captures list.
     *
     * @return void
     */
    public function testFetchCaptureNew()
    {
        $json = <<<JSON
{
    "capture_id": "1003",
    "updated": "from json"
}
JSON;

        $this->mock->addResponse(
            new Response(
                200,
                ['Content-Type' => 'application/json'],
                Stream::factory($json)
            )
        );

        $order = new Order($this->connector, '0002');

        $capture = new Capture($this->connector, $order->getLocation(), '1002');
        $capture['capture_id'] = '1002';
        $capture['updated'] = 'not from json';

        $order['captures'][] = $capture;

        $capture = $order->fetchCapture('1003');
        $this->assertInstanceOf('Klarna\Rest\OrderManagement\Capture', $capture);
        $this->assertEquals(
            '/ordermanagement/v1/orders/0002/captures/1003',
            $capture->getLocation()
        );

        $this->assertEquals('from json', $capture['updated']);
        $this->assertEquals('1003', $capture->getId());
        $this->assertEquals($capture->getId(), $capture['capture_id']);
    }
}
