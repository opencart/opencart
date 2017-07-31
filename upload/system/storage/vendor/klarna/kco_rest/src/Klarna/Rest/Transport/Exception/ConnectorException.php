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
 * File containing the ConnectorException class.
 */

namespace Klarna\Rest\Transport\Exception;

use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Message\ResponseInterface;

/**
 * ConnectorException is used to represent a API error response.
 */
class ConnectorException extends \RuntimeException
{
    /**
     * API response error code.
     *
     * @var string
     */
    protected $errorCode;

    /**
     * API response error messages.
     *
     * @var string[]
     */
    protected $messages;

    /**
     * API response error correlation ID.
     *
     * @var string
     */
    protected $correlationId;

    /**
     * Constructs a connector exception instance.
     *
     * @param array             $data Error data
     * @param RequestException  $prev Previous exception
     */
    public function __construct(
        array $data,
        RequestException $prev
    ) {
        $messages = implode(', ', $data['error_messages']);
        $message = "{$data['error_code']}: {$messages} (#{$data['correlation_id']})";

        parent::__construct($message, $prev->getCode(), $prev);

        $this->errorCode = $data['error_code'];
        $this->messages = $data['error_messages'];
        $this->correlationId = $data['correlation_id'];
    }

    /**
     * Gets the API error code for this exception.
     *
     * @return string
     */
    public function getErrorCode()
    {
        return $this->errorCode;
    }

    /**
     * Gets the API error messages for this exception.
     *
     * @return array
     */
    public function getMessages()
    {
        return $this->messages;
    }

    /**
     * Gets the API correlation ID for this exception.
     *
     * @return string
     */
    public function getCorrelationId()
    {
        return $this->correlationId;
    }

    /**
     * Gets the HTTP response for this API error.
     *
     * @return ResponseInterface
     */
    public function getResponse()
    {
        return $this->getPrevious()->getResponse();
    }
}
