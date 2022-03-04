<?php
/**
 * Copyright 2019 Klarna AB
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
 * File containing the ApiResponse class.
 */

namespace Klarna\Rest\Transport;

/**
 * General HTTP response instance.
 */
class ApiResponse
{
    /**
     * HTTP response Status code
     */
    private $status;

    /**
     * HTTP Response headers
     */
    private $headers = [];

    /**
     * HTTP body binary payout
     */
    private $body = null;


    public function __construct($status = null, $body = null, $headers = [])
    {
        $this->setStatus($status);
        $this->setBody($body);
        $this->setHeaders($headers);
    }
    /**
     * Sets HTTP Status code.
     *
     * @param status HTTP status
     * @return self
     */
    public function setStatus($status)
    {
        $this->status = $status;
        return $this;
    }

    /**
     * Gets HTTP Status code.
     *
     * @return Status code
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Sets binary body payload.
     *
     * @param body Payout
     * @return self
     */
    public function setBody($body)
    {
        $this->body = $body;
        return $this;
    }

    /**
     * Gets binary body payload.
     *
     * @return Payout
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * Sets HTTP headers map
     *
     * @param headers Headers
     * @return self
     */
    public function setHeaders($headers)
    {
        $this->headers = $headers;
        return $this;
    }

    /**
     * Sets single HTTP header value.
     *
     * @param name Header name
     * @param values Header values
     * @return self
     */
    public function setHeader($name, $values)
    {
        $this->headers[$name] = $values;
        return $this;
    }

    /**
     * Gets HTTP Headers map
     *
     * @return Headers
     */
    public function getHeaders()
    {
        return $this->headers;
    }

    /**
     * Gets single header value
     *
     * @param name Header name
     * @return Header values
     */
    public function getHeader($name)
    {
        return isset($this->headers[$name]) ? $this->headers[$name] : null;
    }

    /**
     * Gets the Location header helper.
     *
     * @return string Location if exists, null otherwise
     */
    public function getLocation()
    {
        return empty($this->headers['Location']) ? null : $this->headers['Location'][0];
    }
}
