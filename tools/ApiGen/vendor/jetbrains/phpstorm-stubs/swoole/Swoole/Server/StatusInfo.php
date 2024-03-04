<?php

declare(strict_types=1);

namespace Swoole\Server;

class StatusInfo
{
    public $worker_id = 0;
    public $worker_pid = 0;
    public $status = 0;
    public $exit_code = 0;
    public $signal = 0;
}
