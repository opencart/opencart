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

namespace Klarna\Rest\Tests\Component\Checkout;

use GuzzleHttp\Message\Response;
use GuzzleHttp\Stream\Stream;
use Klarna\Rest\Checkout\Order;
use Klarna\Rest\Tests\Component\ResourceTestCase;
use Klarna\Rest\Transport\Connector;

/**
 * Component test cases for the checkout order resource.
 */
class OrderTest extends ResourceTestCase
{
    /**
     * Make sure that the request sent is correct and that the location is updated.
     *
     * @return void
     */
    public function testCreate()
    {
        $this->mock->addResponse(
            new Response(201, ['Location' => 'http://somewhere/a-path'])
        );

        $order = new Order($this->connector);
        $location = $order->create(['data' => 'goes here'])
            ->getLocation();

        $this->assertEquals('http://somewhere/a-path', $location);

        $request = $this->history->getLastRequest();
        $this->assertEquals('POST', $request->getMethod());
        $this->assertEquals('/checkout/v3/orders', $request->getPath());
        $this->assertEquals('application/json', $request->getHeader('Content-Type'));
        $this->assertEquals('{"data":"goes here"}', strval($request->getBody()));

        $this->assertAuthorization($request);
    }

    /**
     * Make sure that the request sent is correct and that the updated data
     * is accessible.
     *
     * @return void
     */
    public function testUpdate()
    {
        $json = <<<JSON
{
    "order_id": "0001",
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

        $order = new Order($this->connector, '0001');
        $order['updated'] = 'not from json';

        $order->update(['data' => 'sent in']);

        $this->assertEquals('from json', $order['updated']);
        $this->assertEquals('0001', $order->getId());

        $request = $this->history->getLastRequest();
        $this->assertEquals('POST', $request->getMethod());
        $this->assertEquals('/checkout/v3/orders/0001', $request->getPath());
        $this->assertEquals('application/json', $request->getHeader('Content-Type'));
        $this->assertEquals('{"data":"sent in"}', strval($request->getBody()));

        $this->assertAuthorization($request);
    }

    /**
     * Make sure that the request sent and retrieved data is correct.
     *
     * @return void
     */
    public function testFetch()
    {
        $json = <<<JSON
{
    "order_id": "0002",
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
        $order['updated'] = 'not from json';

        $order->fetch();

        $this->assertEquals('from json', $order['updated']);
        $this->assertEquals('0002', $order->getId());

        $request = $this->history->getLastRequest();
        $this->assertEquals('GET', $request->getMethod());
        $this->assertEquals('/checkout/v3/orders/0002', $request->getPath());

        $this->assertAuthorization($request);
    }
}
