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
 * File containing the abstract base resource class.
 */

namespace Klarna\Rest;

use GuzzleHttp\Exception\RequestException;
use Klarna\Rest\Transport\ConnectorInterface;
use Klarna\Rest\Transport\Method;
use Klarna\Rest\Transport\Exception\ConnectorException;
use Klarna\Rest\Transport\ResponseValidator;

/**
 * Abstract resource class.
 */
abstract class Resource extends \ArrayObject
{
    /**
     * Id property field name.
     */
    const ID_FIELD = 'id';

    /**
     * Path to the resource endpoint.
     *
     * @var string
     */
    public static $path;

    /**
     * HTTP transport connector instance.
     *
     * @var Connector
     */
    protected $connector;

    /**
     * Url to the resource.
     *
     * @var string
     */
    protected $url;

    /**
     * Constructs a resource instance.
     *
     * @param ConnectorInterface $connector HTTP transport instance.
     */
    public function __construct(ConnectorInterface $connector)
    {
        $this->connector = $connector;
    }

    /**
     * Gets the resource id.
     *
     * @return string|null
     */
    public function getId()
    {
        return isset($this[static::ID_FIELD]) ? $this[static::ID_FIELD] : null;
    }

    /**
     * Gets the resource location.
     *
     * @return string|null
     */
    public function getLocation()
    {
        return $this->url;
    }

    /**
     * Sets the resource location.
     *
     * @param string $url Url to the resource
     *
     * @return self
     */
    public function setLocation($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Overrides: Stores the ID KEY field in order to restore it after exchanging the array without
     * the ID field.
     * 
     * @param array $array Data to be exchanged
     */
    public function exchangeArray($array)
    {
        $id = $this->getId();

        if (!is_null($array)) {
            parent::exchangeArray($array);
        }
        if (is_null($this->getId()) && !is_null($id)) {
            $this->setId($id);
        }
    }

    /**
     * Fetches the resource.
     *
     * @throws ConnectorException        When the API replies with an error response
     * @throws RequestException          When an error is encountered
     * @throws \RuntimeException         On an unexpected API response
     * @throws \RuntimeException         If the response content type is not JSON
     * @throws \InvalidArgumentException If the JSON cannot be parsed
     * @throws \LogicException           When Guzzle cannot populate the response
     *
     * @return self
     */
    public function fetch()
    {
        $data = $this->get($this->getLocation())
            ->expectSuccessfull()
            ->status('200')
            ->contentType('application/json')
            ->getJson();

        $this->exchangeArray($data);

        return $this;
    }

    /**
     * Sets new ID KEY field.
     *
     * @param mixed $id ID field
     *
     * @return self
     */
    protected function setId($id)
    {
        $this[static::ID_FIELD] = $id;
        return $this;
    }

    /**
     * Sends a HTTP request to the specified url.
     *
     * @param string $method HTTP method, e.g. 'GET'
     * @param string $url Request destination
     * @param array $headers
     * @param string $body
     *
     * @throws ConnectorException When the API replies with an error response
     * @throws RequestException   When an error is encountered
     * @throws \LogicException    When Guzzle cannot populate the response'
     * @return ResponseValidator When the API replies with an error response
     *
     */
    protected function request($method, $url, array $headers = [], $body = null)
    {
        $debug = getenv('DEBUG_SDK') || defined('DEBUG_SDK');

        if ($debug) {
            $methodDebug = str_pad($method, 7, ' ', STR_PAD_LEFT);
            $debugHeaders = json_encode($headers);
            echo <<<DEBUG_BODY
DEBUG MODE: Request
>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>
{$methodDebug} : {$url}
Headers : $debugHeaders
   Body : {$body}
\n
DEBUG_BODY;
        }

        switch ($method) {
            case Method::GET:
                $response = $this->connector->get($url, $headers);
                break;
            case Method::POST:
                $response = $this->connector->post($url, $body, $headers);
                break;
            case Method::PUT:
                $response = $this->connector->put($url, $body, $headers);
                break;
            case Method::DELETE:
                $response = $this->connector->delete($url, $body, $headers);
                break;
            case Method::PATCH:
                $response = $this->connector->patch($url, $body, $headers);
                break;
            default:
                throw new \RuntimeException('Unknown request method ' + $method);
        }

        if ($debug) {
            $debugHeaders = json_encode($response->getHeaders());
            echo <<<DEBUG_BODY
DEBUG MODE: Response
<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<
Headers : $debugHeaders
   Body : {$response->getBody()}
\n
DEBUG_BODY;
        }

        $location =  $response->getLocation();
        if (!empty($location)) {
            $this->setLocation($location);
        }
        
        return new ResponseValidator($response);
    }

    /**
     * Sends a HTTP GET request to the specified url.
     *
     * @param string $url Request destination
     *
     * @throws ConnectorException When the API replies with an error response
     * @throws RequestException   When an error is encountered
     * @throws \LogicException    When Guzzle cannot populate the response
     *
     * @return ResponseValidator
     */
    protected function get($url)
    {
        return $this->request('GET', $url);
    }

    /**
     * Sends a HTTP DELETE request to the specified url.
     *
     * @param string $url  Request destination
     * @param array  $data Data to be JSON encoded
     *
     * @throws ConnectorException When the API replies with an error response
     * @throws RequestException   When an error is encountered
     * @throws \LogicException    When Guzzle cannot populate the response
     *
     * @return ResponseValidator
     */
    protected function delete($url, array $data = null)
    {
        return $this->request(
            'DELETE',
            $url,
            ['Content-Type' => 'application/json'],
            $data !== null ? json_encode($data) : null
        );
    }

    /**
     * Sends a HTTP PATCH request to the specified url.
     *
     * @param string $url  Request destination
     * @param array  $data Data to be JSON encoded
     *
     * @throws ConnectorException When the API replies with an error response
     * @throws RequestException   When an error is encountered
     * @throws \LogicException    When Guzzle cannot populate the response
     *
     * @return ResponseValidator
     */
    protected function patch($url, array $data)
    {
        return $this->request(
            'PATCH',
            $url,
            ['Content-Type' => 'application/json'],
            json_encode($data)
        );
    }

    /**
     * Sends a HTTP PUT request to the specified url.
     *
     * @param string $url  Request destination
     * @param array  $data Data to be JSON encoded
     *
     * @throws ConnectorException When the API replies with an error response
     * @throws RequestException   When an error is encountered
     * @throws \LogicException    When Guzzle cannot populate the response
     *
     * @return ResponseValidator
     */
    protected function put($url, array $data)
    {
        return $this->request(
            'PUT',
            $url,
            ['Content-Type' => 'application/json'],
            json_encode($data)
        );
    }

    /**
     * Sends a HTTP POST request to the specified url.
     *
     * @param string $url  Request destination
     * @param array  $data Data to be JSON encoded
     *
     * @throws ConnectorException When the API replies with an error response
     * @throws RequestException   When an error is encountered
     * @throws \LogicException    When Guzzle cannot populate the response
     *
     * @return ResponseValidator
     */
    protected function post($url, array $data = null)
    {
        return $this->request(
            'POST',
            $url,
            ['Content-Type' => 'application/json'],
            $data !== null ? \json_encode($data) : null
        );
    }
}
