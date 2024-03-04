<?php

declare(strict_types=1);

namespace Swoole\Server;

class Task
{
    public $data;
    public $dispatch_time = 0;
    public $id = -1;
    public $worker_id = -1;
    public $flags = 0;

    /**
     * @param mixed $data
     * @return mixed
     */
    public function finish($data) {}

    /**
     * @param mixed $data
     * @return mixed
     */
    public static function pack($data) {}
}
