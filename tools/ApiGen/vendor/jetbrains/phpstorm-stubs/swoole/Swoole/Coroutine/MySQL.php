<?php

declare(strict_types=1);

namespace Swoole\Coroutine;

class MySQL
{
    public $serverInfo;
    public $sock = -1;
    public $connected = false;
    public $connect_errno = 0;
    public $connect_error = '';
    public $affected_rows = 0;
    public $insert_id = 0;
    public $error = '';
    public $errno = 0;

    public function __construct() {}

    public function __destruct() {}

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
     * @return mixed
     */
    public function connect(array $server_config = null) {}

    /**
     * @param mixed $sql
     * @param mixed|null $timeout
     * @return mixed
     */
    public function query($sql, $timeout = null) {}

    /**
     * @return mixed
     */
    public function fetch() {}

    /**
     * @return mixed
     */
    public function fetchAll() {}

    /**
     * @return mixed
     */
    public function nextResult() {}

    /**
     * @param mixed $query
     * @param mixed|null $timeout
     * @return mixed
     */
    public function prepare($query, $timeout = null) {}

    /**
     * @return mixed
     */
    public function recv() {}

    /**
     * @param mixed|null $timeout
     * @return mixed
     */
    public function begin($timeout = null) {}

    /**
     * @param mixed|null $timeout
     * @return mixed
     */
    public function commit($timeout = null) {}

    /**
     * @param mixed|null $timeout
     * @return mixed
     */
    public function rollback($timeout = null) {}

    /**
     * @param mixed $string
     * @param mixed|null $flags
     * @return mixed
     */
    public function escape($string, $flags = null) {}

    /**
     * @return mixed
     */
    public function close() {}
}
