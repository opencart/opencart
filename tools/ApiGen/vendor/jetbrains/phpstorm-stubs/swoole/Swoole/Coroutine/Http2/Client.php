<?php

declare(strict_types=1);

namespace Swoole\Coroutine\Http2;

class Client
{
    public $errCode = 0;
    public $errMsg = 0;
    public $sock = -1;
    public $type = 0;
    public $setting;
    public $connected = false;
    public $host;
    public $port = 0;
    public $ssl = false;

    public function __construct($host, $port = null, $open_ssl = null) {}

    public function __destruct() {}

    /**
     * @return mixed
     */
    public function set(array $settings) {}

    /**
     * @return mixed
     */
    public function connect() {}

    /**
     * @param mixed|null $key
     * @return mixed
     */
    public function stats($key = null) {}

    /**
     * @param mixed $stream_id
     * @return mixed
     */
    public function isStreamExist($stream_id) {}

    /**
     * @param mixed $request
     * @return mixed
     */
    public function send($request) {}

    /**
     * @param mixed $stream_id
     * @param mixed $data
     * @param mixed|null $end_stream
     * @return mixed
     */
    public function write($stream_id, $data, $end_stream = null) {}

    /**
     * @param mixed|null $timeout
     * @return mixed
     */
    public function recv($timeout = null) {}

    /**
     * @param mixed|null $timeout
     * @return mixed
     */
    public function read($timeout = null) {}

    /**
     * @param mixed|null $error_code
     * @param mixed|null $debug_data
     * @return mixed
     */
    public function goaway($error_code = null, $debug_data = null) {}

    /**
     * @return mixed
     */
    public function ping() {}

    /**
     * @return mixed
     */
    public function close() {}
}
