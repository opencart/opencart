<?php

declare(strict_types=1);

namespace Swoole\Http;

class Response
{
    public $fd = 0;
    public $socket;
    public $header;
    public $cookie;
    public $trailer;

    public function __destruct() {}

    /**
     * @return mixed
     */
    public function initHeader() {}

    /**
     * @return mixed
     */
    public function isWritable() {}

    /**
     * @param mixed $name
     * @param mixed|null $value
     * @param mixed|null $expires
     * @param mixed|null $path
     * @param mixed|null $domain
     * @param mixed|null $secure
     * @param mixed|null $httponly
     * @param mixed|null $samesite
     * @param mixed|null $priority
     * @return mixed
     */
    public function cookie($name, $value = null, $expires = null, $path = null, $domain = null, $secure = null, $httponly = null, $samesite = null, $priority = null) {}

    /**
     * @param mixed $name
     * @param mixed|null $value
     * @param mixed|null $expires
     * @param mixed|null $path
     * @param mixed|null $domain
     * @param mixed|null $secure
     * @param mixed|null $httponly
     * @param mixed|null $samesite
     * @param mixed|null $priority
     * @return mixed
     */
    public function setCookie($name, $value = null, $expires = null, $path = null, $domain = null, $secure = null, $httponly = null, $samesite = null, $priority = null) {}

    /**
     * @param mixed $name
     * @param mixed|null $value
     * @param mixed|null $expires
     * @param mixed|null $path
     * @param mixed|null $domain
     * @param mixed|null $secure
     * @param mixed|null $httponly
     * @param mixed|null $samesite
     * @param mixed|null $priority
     * @return mixed
     */
    public function rawcookie($name, $value = null, $expires = null, $path = null, $domain = null, $secure = null, $httponly = null, $samesite = null, $priority = null) {}

    /**
     * @param mixed $http_code
     * @param mixed|null $reason
     * @return mixed
     */
    public function status($http_code, $reason = null) {}

    /**
     * @param mixed $http_code
     * @param mixed|null $reason
     * @return mixed
     */
    public function setStatusCode($http_code, $reason = null) {}

    /**
     * @param mixed $key
     * @param mixed $value
     * @param mixed|null $format
     * @return mixed
     */
    public function header($key, $value, $format = null) {}

    /**
     * @param mixed $key
     * @param mixed $value
     * @param mixed|null $format
     * @return mixed
     */
    public function setHeader($key, $value, $format = null) {}

    /**
     * @param mixed $key
     * @param mixed $value
     * @return mixed
     */
    public function trailer($key, $value) {}

    /**
     * @return mixed
     */
    public function ping() {}

    /**
     * @return mixed
     */
    public function goaway() {}

    /**
     * @param mixed $content
     * @return mixed
     */
    public function write($content) {}

    /**
     * @param mixed|null $content
     * @return mixed
     */
    public function end($content = null) {}

    /**
     * @param mixed $filename
     * @param mixed|null $offset
     * @param mixed|null $length
     * @return mixed
     */
    public function sendfile($filename, $offset = null, $length = null) {}

    /**
     * @param mixed $location
     * @param mixed|null $http_code
     * @return mixed
     */
    public function redirect($location, $http_code = null) {}

    /**
     * @return mixed
     */
    public function detach() {}

    /**
     * @param mixed $server
     * @param mixed|null $fd
     * @return mixed
     */
    public static function create($server, $fd = null) {}

    /**
     * @return mixed
     */
    public function upgrade() {}

    /**
     * @param mixed $data
     * @param mixed|null $opcode
     * @param mixed|null $flags
     * @return mixed
     */
    public function push($data, $opcode = null, $flags = null) {}

    /**
     * @return mixed
     */
    public function recv() {}

    /**
     * @return mixed
     */
    public function close() {}
}
