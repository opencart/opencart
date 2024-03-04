<?php

declare(strict_types=1);

namespace Swoole\Coroutine;

class Channel
{
    public $capacity = 0;
    public $errCode = 0;

    public function __construct($size = null) {}

    /**
     * @param mixed $data
     * @param mixed|null $timeout
     * @return mixed
     */
    public function push($data, $timeout = null) {}

    /**
     * @param mixed|null $timeout
     * @return mixed
     */
    public function pop($timeout = null) {}

    /**
     * @return mixed
     */
    public function isEmpty() {}

    /**
     * @return mixed
     */
    public function isFull() {}

    /**
     * @return mixed
     */
    public function close() {}

    /**
     * @return mixed
     */
    public function stats() {}

    /**
     * @return mixed
     */
    public function length() {}
}
