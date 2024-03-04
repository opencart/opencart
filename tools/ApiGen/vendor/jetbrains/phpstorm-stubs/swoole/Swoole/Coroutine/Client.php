<?php

declare(strict_types=1);

namespace Swoole\Coroutine;

class Client
{
    public const MSG_OOB = 1;
    public const MSG_PEEK = 2;
    public const MSG_DONTWAIT = 64;
    public const MSG_WAITALL = 256;
    public $errCode = 0;
    public $errMsg = '';
    public $fd = -1;
    public $type = 1;
    public $setting;
    public $connected = false;
    private $socket;

    public function __construct($type) {}

    public function __destruct() {}

    /**
     * @return mixed
     */
    public function set(array $settings) {}

    /**
     * @param mixed $host
     * @param mixed|null $port
     * @param mixed|null $timeout
     * @param mixed|null $sock_flag
     * @return mixed
     */
    public function connect($host, $port = null, $timeout = null, $sock_flag = null) {}

    /**
     * @param mixed|null $timeout
     * @return mixed
     */
    public function recv($timeout = null) {}

    /**
     * @param mixed|null $length
     * @return mixed
     */
    public function peek($length = null) {}

    /**
     * @param mixed $data
     * @return mixed
     */
    public function send($data) {}

    /**
     * @param mixed $filename
     * @param mixed|null $offset
     * @param mixed|null $length
     * @return mixed
     */
    public function sendfile($filename, $offset = null, $length = null) {}

    /**
     * @param mixed $address
     * @param mixed $port
     * @param mixed $data
     * @return mixed
     */
    public function sendto($address, $port, $data) {}

    /**
     * @param mixed $length
     * @param mixed $address
     * @param mixed|null $port
     * @return mixed
     */
    public function recvfrom($length, &$address, &$port = null) {}

    /**
     * @return mixed
     */
    public function enableSSL() {}

    /**
     * @return mixed
     */
    public function getPeerCert() {}

    /**
     * @return mixed
     */
    public function verifyPeerCert() {}

    /**
     * @return mixed
     */
    public function isConnected() {}

    /**
     * @return mixed
     */
    public function getsockname() {}

    /**
     * @return mixed
     */
    public function getpeername() {}

    /**
     * @return mixed
     */
    public function close() {}

    /**
     * @return mixed
     */
    public function exportSocket() {}
}
