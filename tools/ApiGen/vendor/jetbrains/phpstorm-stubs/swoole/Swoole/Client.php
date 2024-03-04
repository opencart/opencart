<?php

declare(strict_types=1);

namespace Swoole;

class Client
{
    public const MSG_OOB = 1;
    public const MSG_PEEK = 2;
    public const MSG_DONTWAIT = 64;
    public const MSG_WAITALL = 256;
    public const SHUT_RDWR = 2;
    public const SHUT_RD = 0;
    public const SHUT_WR = 1;
    public $errCode = 0;
    public $sock = -1;
    public $reuse = false;
    public $reuseCount = 0;
    public $type = 0;
    public $id;
    public $setting;

    public function __construct($type, $async = null, $id = null) {}

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
     * @param mixed|null $size
     * @param mixed|null $flag
     * @return mixed
     */
    public function recv($size = null, $flag = null) {}

    /**
     * @param mixed $data
     * @param mixed|null $flag
     * @return mixed
     */
    public function send($data, $flag = null) {}

    /**
     * @param mixed $filename
     * @param mixed|null $offset
     * @param mixed|null $length
     * @return mixed
     */
    public function sendfile($filename, $offset = null, $length = null) {}

    /**
     * @param mixed $ip
     * @param mixed $port
     * @param mixed $data
     * @return mixed
     */
    public function sendto($ip, $port, $data) {}

    /**
     * @param mixed $how
     * @return mixed
     */
    public function shutdown($how) {}

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
     * @param mixed|null $force
     * @return mixed
     */
    public function close($force = null) {}

    /**
     * @return mixed
     */
    public function getSocket() {}
}
