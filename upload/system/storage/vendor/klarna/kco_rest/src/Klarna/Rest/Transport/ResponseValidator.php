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
 * File containing the ResponseValidator class.
 */

namespace Klarna\Rest\Transport;

use Klarna\Rest\Transport\ApiResponse;
use Klarna\Rest\Transport\Exception\ConnectorException;

/**
 * HTTP response validator helper class.
 */
class ResponseValidator
{
    /**
     * HTTP response to validate against.
     *
     * @var ApiResponse
     */
    protected $response;

    /**
     * Constructs a response validator instance.
     *
     * @param ApiResponse $response Response to validate
     */
    public function __construct(ApiResponse $response)
    {
        $this->response = $response;
    }

    /**
     * Gets the response object.
     *
     * @return ApiResponse
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * Asserts the HTTP response status code.
     *
     * @param string|string[] $status Expected status code(s)
     *
     * @throws \RuntimeException If status code does not match
     *
     * @return self
     */
    public function status($status)
    {
        $httpStatus = (string) $this->response->getStatus();
        if (is_array($status) && !in_array($httpStatus, $status)) {
            throw new \RuntimeException(
                "Unexpected response status code: {$httpStatus}"
            );
        }

        if (is_string($status) && $httpStatus !== $status) {
            throw new \RuntimeException(
                "Unexpected response status code: {$httpStatus}"
            );
        }

        return $this;
    }

    /**
     * Asserts the Content-Type header. Checks partial matching.
     * Validation PASSES in the following cases:
     *      Content-Type: application/json
     *      $mediaType = 'application/json'
     *
     *      Content-Type: application/json; charset=utf-8
     *      $mediaType = 'application/json'
     *
     * Validation FAILS in the following cases:
     *      Content-Type: plain/text
     *      $mediaType = 'application/json'
     *
     *      Content-Type: application/json; charset=utf-8
     *      $mediaType = 'application/json; charset=cp-1251'
     *
     * @param string $mediaType Expected media type. RegExp rules can be used.
     *
     * @throws \RuntimeException If Content-Type header is missing
     * @throws \RuntimeException If Content-Type header does not match
     *
     * @return self
     */
    public function contentType($mediaType)
    {
        $contentType = $this->response->getHeader('Content-Type');
        if (empty($contentType)) {
            throw new \RuntimeException('Response is missing a Content-Type header');
        }
        $mediaFound = false;
        foreach ($contentType as $type) {
            if (preg_match('#' . $mediaType . '#', $type)) {
                $mediaFound = true;
                break;
            }
        }

        if (!$mediaFound) {
            throw new \RuntimeException(
                'Unexpected Content-Type header received: '
                . implode(',', $contentType) . '. Expected: ' . $mediaType
            );
        }

        return $this;
    }

    /**
     * Gets the decoded JSON response.
     *
     * @throws \RuntimeException         If the response body is not in JSON format
     * @throws \InvalidArgumentException If the JSON cannot be parsed
     *
     * @return array
     */
    public function getJson()
    {
        return \json_decode($this->response->getBody(), true);
    }

    /**
     * Gets response body.
     *
     * @throws \RuntimeException         If the response body is not in JSON format
     * @throws \InvalidArgumentException If the JSON cannot be parsed
     *
     * @return StreamInterface the body as a stream
     */
    public function getBody()
    {
        return $this->response->getBody();
    }

    /**
     * Gets the Location header.
     *
     * @throws \RuntimeException If the Location header is missing
     *
     * @return string
     */
    public function getLocation()
    {
        $location = $this->response->getHeader('Location');
        if (empty($location)) {
            throw new \RuntimeException('Response is missing a Location header');
        }
        return $location[0];
    }

    
    /**
     * Asserts and analyze the response. Checks if the reponse has SUCCESSFULL family
     * and try to parse the Klarna error message if possbile.
     *
     * @throws ConnectorException if response has non-2xx HTTP CODE and contains
     *                      a <a href="https://developers.klarna.com/api/#errors">Error</a>
     * @throws \RuntimeException if response has non-2xx HTTP CODE and body is not parsable
     *
     * @return void
     */
    public function expectSuccessfull()
    {
        if ($this->isSuccessfull()) {
            return $this;
        }

        $data = json_decode($this->response->getBody(), true);
        if (is_array($data) && array_key_exists('error_code', $data)) {
            throw new ConnectorException($data, $this->response->getStatus());
        }

        throw new \RuntimeException(
            'Unexpected reponse HTTP status ' . $this->response->getStatus() .
            '. Excepted HTTP status should be in 2xx range',
            $this->response->getStatus()
        );
    }

    public function isSuccessfull()
    {
        $status = $this->response->getStatus();
        return $status >= 200 && $status < 300;
    }
}
