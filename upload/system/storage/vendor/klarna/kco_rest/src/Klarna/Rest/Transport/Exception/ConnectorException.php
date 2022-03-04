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
     * @param array $data Error data
     */
    public function __construct(
        array $data,
        $code = 0
    ) {
        $data = self::setDefaultData($data);

        $messages = implode(', ', $data['error_messages']);
        $serviceVersion = isset($data['service_version']) ? $data['service_version'] : '';
        $message = "{$data['error_code']}: {$messages} (#{$data['correlation_id']})";
        $message .= $serviceVersion ? " ServiceVersion: $serviceVersion" : '';

        parent::__construct($message, $code);

        $this->errorCode = $data['error_code'];
        $this->messages = $data['error_messages'];
        $this->correlationId = $data['correlation_id'];
        $this->serviceVersion = $serviceVersion;
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
     * Gets the API Service version for this exception.
     *
     * @return string
     */
    public function getServiceVersion()
    {
        return $this->serviceVersion;
    }

    /**
     * @deprecated Function is not longer used. Will always return null
     * Gets the HTTP response for this API error.
     *
     * @return null
     */
    public function getResponse()
    {
        return null;
    }

    private static function setDefaultData($data)
    {
        $defaults = [
            'error_code' => 'UNDEFINED',
            'error_messages' => [],
            'correlation_id' => 'UNDEFINED',
        ];

        foreach ($defaults as $field => $default) {
            if (!isset($data[$field])) {
                $data[$field] = $default;
            }
        }

        // We need to have a special check for error_message and merge the message to error_messages
        if (isset($data['error_message'])) {
            array_push($data['error_messages'], $data['error_message']);
        }

        return $data;
    }
}
