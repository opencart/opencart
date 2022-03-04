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
 * File containing the Connector class.
 */

namespace Klarna\Rest\Transport;

use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\TransportException;
use GuzzleHttp\Psr7\Request;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * Transport connector used to authenticate and make HTTP requests against the
 * Klarna APIs. Transport uses Guzzle HTTP client to perform HTTP(s) calls.
 */
class GuzzleConnector implements ConnectorInterface
{
    /**
     * HTTP transport client.
     *
     * @var ClientInterface
     */
    protected $client;

    /**
     * Merchant ID.
     *
     * @var string
     */
    protected $merchantId;

    /**
     * Shared secret.
     *
     * @var string
     */
    protected $sharedSecret;

    /**
     * HTTP user agent.
     *
     * @var UserAgent
     */
    protected $userAgent;

    /**
     * Constructs a connector instance.
     *
     * Example usage:
     *
     *     $client = new \GuzzleHttp\Client(['base_url' => 'https://api.klarna.com']);
     *     $connector = new \Klarna\Transport\Connector($client, '0', 'sharedSecret');
     *
     *
     * @param ClientInterface    $client       HTTP transport client
     * @param string             $merchantId   Merchant ID
     * @param string             $sharedSecret Shared secret
     * @param UserAgentInterface $userAgent    HTTP user agent to identify the client
     */
    public function __construct(
        ClientInterface $client,
        $merchantId,
        $sharedSecret,
        UserAgentInterface $userAgent = null
    ) {
        $this->client = $client;
        $this->merchantId = $merchantId;
        $this->sharedSecret = $sharedSecret;

        if ($userAgent === null) {
            $userAgent = UserAgent::createDefault(['Guzzle/' . ClientInterface::VERSION]);
        }
        $this->userAgent = $userAgent;
    }

    /**
     * Sends HTTP GET request to specified path.
     *
     * @param string $path URL path.
     * @param array $headers HTTP request headers
     * @return ApiResponse Processed response
     *
     * @throws RuntimeException if HTTP transport failed to execute a call
     */
    public function get($path, $headers = [])
    {
        $request = $this->createRequest($path, Method::GET, $headers);
        $response = $this->send($request);
        return $this->getApiResponse($response);
    }

    /**
     * Sends HTTP POST request to specified path.
     *
     * @param string $path URL path.
     * @param string $data Data to be sent to API server in a payload. Example: json-encoded string
     * @param array $headers HTTP request headers
     * @return ApiResponse Processed response
     *
     * @throws RuntimeException if HTTP transport failed to execute a call
     */
    public function post($path, $data = null, $headers = [])
    {
        $request = $this->createRequest($path, Method::POST, $headers, $data);
        $response = $this->send($request);
        return $this->getApiResponse($response);
    }

    /**
     * Sends HTTP PUT request to specified path.
     *
     * @param string $path URL path.
     * @param string $data Data to be sent to API server in a payload. Example: json-encoded string
     * @param array $headers HTTP request headers
     * @return ApiResponse Processed response
     *
     * @throws RuntimeException if HTTP transport failed to execute a call
     */
    public function put($path, $data = null, $headers = [])
    {
        $request = $this->createRequest($path, Method::PUT, $headers, $data);
        $response = $this->send($request);
        return $this->getApiResponse($response);
    }

    /**
     * Sends HTTP PATCH request to specified path.
     *
     * @param string $path URL path.
     * @param string $data Data to be sent to API server in a payload. Example: json-encoded string
     * @param array $headers HTTP request headers
     * @return ApiResponse Processed response
     *
     * @throws RuntimeException if HTTP transport failed to execute a call
     */
    public function patch($path, $data = null, $headers = [])
    {
        $request = $this->createRequest($path, Method::PATCH, $headers, $data);
        $response = $this->send($request);
        return $this->getApiResponse($response);
    }

    /**
     * Sends HTTP DELETE request to specified path.
     *
     * @param string $path URL path.
     * @param string $data Data to be sent to API server in a payload. Example: json-encoded string
     * @param array $headers HTTP request headers
     * @return ApiResponse Processed response
     *
     * @throws RuntimeException if HTTP transport failed to execute a call
     */
    public function delete($path, $data = null, $headers = [])
    {
        $request = $this->createRequest($path, Method::DELETE, $headers, $data);
        $response = $this->send($request);
        return $this->getApiResponse($response);
    }

    /**
     * Converts ResponseInterface to ApiResponse.
     *
     * @param response ResponseInterface intance
     * @return ApiResponse
     */
    protected function getApiResponse(ResponseInterface $response)
    {
        return new ApiResponse(
            $response->getStatusCode(),
            $response->getBody()->getContents(),
            $response->getHeaders()
        );
    }

    /**
     * @deprecated No longer used and not recommended. Use direct get, post, put, delete and patch methods instead.
     * Creates a request object.
     *
     * @param string $url     URL
     * @param string $method  HTTP method
     *
     * @return RequestInterface
     */
    public function createRequest($url, $method = 'GET', array $headers = [], $body = null)
    {
        $headers = array_merge($headers, ['User-Agent' => strval($this->userAgent)]);
        return new Request($method, $url, $headers, $body);
    }

    /**
     * @deprecated No longer used and not recommended. Use direct get, post, put, delete and patch methods instead.
     * Sends the request.
     *
     * @param RequestInterface $request Request to send
     * @param string[] $options Request options
     *
     * @throws RequestException   When an error is encountered
     * @throws \RuntimeException  When the adapter does not populate a response
     *
     * @return ResponseInterface
     */
    public function send(RequestInterface $request, array $options = [])
    {
        $requestOptions = $this->client->getConfig('request');
        if (is_array($requestOptions)) {
            $options = array_merge($requestOptions, $options);
        }
        $options['auth'] = [$this->merchantId, $this->sharedSecret, 'basic'];
        $options['http_errors'] = false;

        try {
            $response = $this->client->send($request, $options);
            return $response;
        } catch (RequestException $e) {
            throw new \RuntimeException($e->getMessage(), $e->getCode(), $e);
        } catch (\Throwable $e) {
            throw new \RuntimeException($e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * Gets the HTTP transport client.
     *
     * @return ClientInterface
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * Gets the user agent.
     *
     * @return UserAgentInterface
     */
    public function getUserAgent()
    {
        return $this->userAgent;
    }

    /**
     * Factory method to create a connector instance.
     *
     * @param string             $merchantId   Merchant ID
     * @param string             $sharedSecret Shared secret
     * @param string             $baseUrl      Base URL for HTTP requests
     * @param UserAgentInterface $userAgent    HTTP user agent to identify the client
     *
     * @return self
     */
    public static function create(
        $merchantId,
        $sharedSecret,
        $baseUrl = self::EU_BASE_URL,
        UserAgentInterface $userAgent = null
    ) {
        $client = new Client(['base_uri' => $baseUrl]);

        return new static($client, $merchantId, $sharedSecret, $userAgent);
    }
}
