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

namespace Klarna\Rest\Tests\Component\OrderManagement;

use GuzzleHttp\Message\Response;
use GuzzleHttp\Stream\Stream;
use Klarna\Rest\OrderManagement\Capture;
use Klarna\Rest\Tests\Component\ResourceTestCase;
use Klarna\Rest\Transport\Connector;

/**
 * Component test cases for the capture resource.
 */
class CaptureTest extends ResourceTestCase
{
    /**
     * Make sure that the request sent and retrieved data is correct.
     *
     * @return void
     */
    public function testFetch()
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

        $capture = new Capture($this->connector, '/path', '1002');
        $capture['updated'] = 'not from json';

        $capture->fetch();

        $this->assertEquals('from json', $capture['updated']);
        $this->assertEquals('1002', $capture->getId());

        $request = $this->history->getLastRequest();
        $this->assertEquals('GET', $request->getMethod());
        $this->assertEquals('/path/captures/1002', $request->getPath());

        $this->assertAuthorization($request);
    }

    /**
     * Make sure that the request sent is correct and that the location is updated
     * when creating the capture.
     *
     * @return void
     */
    public function testCreate()
    {
        $this->mock->addResponse(
            new Response(201, ['Location' => 'http://somewhere/a-path'])
        );

        $capture = new Capture($this->connector, '/path/to/order');
        $location = $capture->create(['data' => 'goes here'])
            ->getLocation();

        $this->assertEquals('http://somewhere/a-path', $location);

        $request = $this->history->getLastRequest();
        $this->assertEquals('POST', $request->getMethod());
        $this->assertEquals('/path/to/order/captures', $request->getPath());
        $this->assertEquals('application/json', $request->getHeader('Content-Type'));
        $this->assertEquals('{"data":"goes here"}', strval($request->getBody()));

        $this->assertAuthorization($request);
    }

    /**
     * Make sure that the request sent is correct when appending shipping info.
     *
     * @return void
     */
    public function testAddShippingInfo()
    {
        $this->mock->addResponse(new Response(204));

        $capture = new Capture($this->connector, '/order/0002', '1002');
        $capture->addShippingInfo(['data' => 'sent in']);

        $request = $this->history->getLastRequest();
        $this->assertEquals('POST', $request->getMethod());
        $this->assertEquals(
            '/order/0002/captures/1002/shipping-info',
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

        $capture = new Capture($this->connector, '/order/0002', '1002');
        $capture->updateCustomerDetails(['data' => 'sent in']);

        $request = $this->history->getLastRequest();
        $this->assertEquals('PATCH', $request->getMethod());
        $this->assertEquals(
            '/order/0002/captures/1002/customer-details',
            $request->getPath()
        );

        $this->assertEquals('application/json', $request->getHeader('Content-Type'));
        $this->assertEquals('{"data":"sent in"}', strval($request->getBody()));

        $this->assertAuthorization($request);
    }

    /**
     * Make sure that the request sent is correct when triggering a send-out.
     *
     * @return void
     */
    public function testTriggerSendout()
    {
        $this->mock->addResponse(new Response(204));

        $capture = new Capture($this->connector, '/order/0002', '1002');
        $capture->triggerSendout();

        $request = $this->history->getLastRequest();
        $this->assertEquals('POST', $request->getMethod());
        $this->assertEquals(
            '/order/0002/captures/1002/trigger-send-out',
            $request->getPath()
        );

        $this->assertAuthorization($request);
    }
}
