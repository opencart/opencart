<?php

declare(strict_types=1);

namespace Swoole\Coroutine\Http;

class Client
{
    public $errCode = 0;
    public $errMsg = '';
    public $connected = false;
    public $host = '';
    public $port = 0;
    public $ssl = false;
    public $setting;
    public $requestMethod;
    public $requestHeaders;
    public $requestBody;
    public $uploadFiles;
    public $downloadFile;
    public $downloadOffset = 0;
    public $statusCode = 0;
    public $headers;
    public $set_cookie_headers;
    public $cookies;
    public $body = '';

    public function __construct($host, $port = null, $ssl = null) {}

    public function __destruct() {}

    /**
     * @return mixed
     */
    public function set(array $settings) {}

    /**
     * @return mixed
     */
    public function getDefer() {}

    /**
     * @param mixed|null $defer
     * @return mixed
     */
    public function setDefer($defer = null) {}

    /**
     * @param mixed $method
     * @return mixed
     */
    public function setMethod($method) {}

    /**
     * @return mixed
     */
    public function setHeaders(array $headers) {}

    /**
     * @param mixed $username
     * @param mixed $password
     * @return mixed
     */
    public function setBasicAuth($username, $password) {}

    /**
     * @return mixed
     */
    public function setCookies(array $cookies) {}

    /**
     * @param mixed $data
     * @return mixed
     */
    public function setData($data) {}

    /**
     * @param mixed $path
     * @param mixed $name
     * @param mixed|null $type
     * @param mixed|null $filename
     * @param mixed|null $offset
     * @param mixed|null $length
     * @return mixed
     */
    public function addFile($path, $name, $type = null, $filename = null, $offset = null, $length = null) {}

    /**
     * @param mixed $path
     * @param mixed $name
     * @param mixed|null $type
     * @param mixed|null $filename
     * @return mixed
     */
    public function addData($path, $name, $type = null, $filename = null) {}

    /**
     * @param mixed $path
     * @return mixed
     */
    public function execute($path) {}

    /**
     * @return mixed
     */
    public function getpeername() {}

    /**
     * @return mixed
     */
    public function getsockname() {}

    /**
     * @param mixed $path
     * @return mixed
     */
    public function get($path) {}

    /**
     * @param mixed $path
     * @param mixed $data
     * @return mixed
     */
    public function post($path, $data) {}

    /**
     * @param mixed $path
     * @param mixed $file
     * @param mixed|null $offset
     * @return mixed
     */
    public function download($path, $file, $offset = null) {}

    /**
     * @return mixed
     */
    public function getBody() {}

    /**
     * @return mixed
     */
    public function getHeaders() {}

    /**
     * @return mixed
     */
    public function getCookies() {}

    /**
     * @return mixed
     */
    public function getStatusCode() {}

    /**
     * @return mixed
     */
    public function getHeaderOut() {}

    /**
     * @return mixed
     */
    public function getPeerCert() {}

    /**
     * @param mixed $path
     * @return mixed
     */
    public function upgrade($path) {}

    /**
     * @param mixed $data
     * @param mixed|null $opcode
     * @param mixed|null $flags
     * @return mixed
     */
    public function push($data, $opcode = null, $flags = null) {}

    /**
     * @param mixed|null $timeout
     * @return mixed
     */
    public function recv($timeout = null) {}

    /**
     * @return mixed
     */
    public function close() {}
}
