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
 * File containing tests for the ResponseValidator class.
 */

namespace Klarna\Rest\Tests\Unit\Transport;

use Klarna\Rest\Transport\ResponseValidator;

/**
 * Unit test cases for the ResponseValidator class.
 */
class ResponseValidatorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var GuzzleHttp\Message\ResponseInterface
     */
    protected $response;

    /**
     * @var ResponseValidator
     */
    protected $validator;

    /**
     * Set up the test fixtures
     */
    protected function setUp()
    {
        $interface = 'GuzzleHttp\Message\ResponseInterface';
        $this->response = $this->getMockBuilder($interface)
            ->getMock();

        $this->validator = new ResponseValidator($this->response);
    }

    /**
     * Make sure the response is retrievable.
     *
     * @return void
     */
    public function testGetResponse()
    {
        $this->assertSame($this->response, $this->validator->getResponse());
    }

    /**
     * Make sure that the JSON data is possible to retrieve.
     *
     * @return void
     */
    public function testGetJson()
    {
        $this->response->expects($this->once())
            ->method('json')
            ->will($this->returnValue('json response'));

        $this->assertEquals('json response', $this->validator->getJson());
    }

    /**
     * Make sure that the location header can be retrieved.
     *
     * @return void
     */
    public function testGetLocation()
    {
        $this->response->expects($this->once())
            ->method('hasHeader')
            ->will($this->returnValue(true));

        $this->response->expects($this->once())
            ->method('getHeader')
            ->with('Location')
            ->will($this->returnValue('a location'));

        $this->assertEquals('a location', $this->validator->getLocation());
    }

    /**
     * Make sure that a missing Location header throws an exception.
     *
     * @return void
     */
    public function testGetLocationException()
    {
        $this->response->expects($this->once())
            ->method('hasHeader')
            ->with('Location')
            ->will($this->returnValue(false));

        $this->setExpectedException(
            'RuntimeException',
            'Response is missing a Location header'
        );

        $this->validator->getLocation();
    }

    /**
     * Make sure that the content type is asserted properly.
     *
     * @return void
     */
    public function testContentType()
    {
        $this->response->expects($this->once())
            ->method('hasHeader')
            ->will($this->returnValue(true));

        $this->response->expects($this->once())
            ->method('getHeader')
            ->with('Content-Type')
            ->will($this->returnValue('text/plain'));

        $this->assertSame(
            $this->validator,
            $this->validator->contentType('text/plain')
        );
    }

    /**
     * Make sure that a missing Content-Type header throws an exception.
     *
     * @return void
     */
    public function testContentTypeMissingException()
    {
        $this->response->expects($this->once())
            ->method('hasHeader')
            ->with('Content-Type')
            ->will($this->returnValue(false));

        $this->setExpectedException(
            'RuntimeException',
            'Response is missing a Content-Type header'
        );

        $this->validator->contentType('text/plain');
    }

    /**
     * Make sure that a different Content-Type header throws an exception.
     *
     * @return void
     */
    public function testContentTypeWrongException()
    {
        $this->response->expects($this->once())
            ->method('hasHeader')
            ->with('Content-Type')
            ->will($this->returnValue(true));

        $this->response->expects($this->once())
            ->method('getHeader')
            ->with('Content-Type')
            ->will($this->returnValue('text/plain'));

        $this->setExpectedException(
            'RuntimeException',
            'Unexpected Content-Type header received: text/plain'
        );

        $this->validator->contentType('application/json');
    }

    /**
     * Make sure that the status code is asserted properly.
     *
     * @return void
     */
    public function testStatus()
    {
        $this->response->expects($this->once())
            ->method('getStatusCode')
            ->will($this->returnValue('200'));

        $this->assertSame($this->validator, $this->validator->status('200'));
    }

    /**
     * Make sure that multiple status codes are asserted properly.
     *
     * @return void
     */
    public function testStatuses()
    {
        $this->response->expects($this->once())
            ->method('getStatusCode')
            ->will($this->returnValue('204'));

        $this->assertSame(
            $this->validator,
            $this->validator->status(['201', '204'])
        );
    }

    /**
     * Make sure that a different status code throws an exception.
     *
     * @return void
     */
    public function testStatusException()
    {
        $this->response->expects($this->once())
            ->method('getStatusCode')
            ->will($this->returnValue('201'));

        $this->setExpectedException(
            'RuntimeException',
            'Unexpected response status code: 201'
        );

        $this->validator->status('200');
    }

    /**
     * Make sure that a different status code throws an exception.
     *
     * @return void
     */
    public function testStatusesException()
    {
        $this->response->expects($this->once())
            ->method('getStatusCode')
            ->will($this->returnValue('200'));

        $this->setExpectedException(
            'RuntimeException',
            'Unexpected response status code: 200'
        );

        $this->validator->status(['201', '204']);
    }
}
