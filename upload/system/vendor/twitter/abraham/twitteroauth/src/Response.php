<?php

namespace Abraham\TwitterOAuth;

/**
 * The result of the most recent API request.
 *
 * @author Abraham Williams <abraham@abrah.am>
 */
class Response
{
    /** @var string|null API path from the most recent request */
    private $apiPath;
    /** @var int HTTP status code from the most recent request */
    private $httpCode = 0;
    /** @var array HTTP headers from the most recent request */
    private $headers = [];
    /** @var array|object Response body from the most recent request */
    private $body = [];
    /** @var array HTTP headers from the most recent request that start with X */
    private $xHeaders = [];

    /**
     * @param string $apiPath
     */
    public function setApiPath($apiPath)
    {
        $this->apiPath = $apiPath;
    }

    /**
     * @return string|null
     */
    public function getApiPath()
    {
        return $this->apiPath;
    }

    /**
     * @param array|object $body
     */
    public function setBody($body)
    {
        $this->body = $body;
    }

    /**
     * @return array|object|string
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * @param int $httpCode
     */
    public function setHttpCode($httpCode)
    {
        $this->httpCode = $httpCode;
    }

    /**
     * @return int
     */
    public function getHttpCode()
    {
        return $this->httpCode;
    }

    /**
     * @param array $headers
     */
    public function setHeaders($headers)
    {
        foreach ($headers as $key => $value) {
            if (substr($key, 0, 1) == 'x') {
                $this->xHeaders[$key] = $value;
            }
        }
        $this->headers = $headers;
    }

    /**
     * @return array
     */
    public function getsHeaders()
    {
        return $this->headers;
    }

    /**
     * @param array $xHeaders
     */
    public function setXHeaders($xHeaders)
    {
        $this->xHeaders = $xHeaders;
    }

    /**
     * @return array
     */
    public function getXHeaders()
    {
        return $this->xHeaders;
    }
}
