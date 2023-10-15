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
 * File containing tests for the ConnectorException class.
 */

namespace Klarna\Rest\Tests\Unit\Transport\Exception;

use GuzzleHttp\Exception\RequestException;
use Klarna\Rest\Transport\Exception\ConnectorException;

/**
 * Unit test cases for the ConnectorException.
 */
class ConnectorExceptionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Make sure the getters work as intended.
     *
     * @return void
     */
    public function testGetters()
    {
        $data = [
            'error_code' => 'ERROR_CODE_1',
            'error_messages' => [
                'Oh dear...',
                'Oh no...'
            ],
            'correlation_id' => 'corr_id_1'
        ];

        $request = $this->getMockBuilder('GuzzleHttp\Message\RequestInterface')
            ->getMock();
        $response = $this->getMockBuilder('GuzzleHttp\Message\ResponseInterface')
            ->getMock();

        $response->expects($this->once())
            ->method('getStatusCode')
            ->will($this->returnValue('500'));

        $previous = new RequestException('Error', $request, $response);

        $exception = new ConnectorException($data, $previous);

        $this->assertEquals(500, $exception->getCode());
        $this->assertSame($response, $exception->getResponse());

        $this->assertContains(
            $data['error_messages'][0],
            $exception->getMessages()
        );
        $this->assertContains(
            $data['error_messages'][1],
            $exception->getMessages()
        );

        $this->assertEquals($data['error_code'], $exception->getErrorCode());
        $this->assertEquals($data['correlation_id'], $exception->getCorrelationId());

        $this->assertSame($previous, $exception->getPrevious());
        $this->assertEquals(
            'ERROR_CODE_1: Oh dear..., Oh no... (#corr_id_1)',
            $exception->getMessage()
        );
    }
}
