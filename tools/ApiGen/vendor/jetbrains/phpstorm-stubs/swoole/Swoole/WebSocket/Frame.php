<?php

declare(strict_types=1);

namespace Swoole\WebSocket;

class Frame
{
    public $fd = 0;
    public $data = '';
    public $opcode = 1;
    public $flags = 1;
    public $finish;

    public function __toString(): string {}

    /**
     * @param mixed $data
     * @param mixed|null $opcode
     * @param mixed|null $flags
     * @return mixed
     */
    public static function pack($data, $opcode = null, $flags = null) {}

    /**
     * @param mixed $data
     * @return mixed
     */
    public static function unpack($data) {}
}
