<?php

declare(strict_types=1);

namespace Swoole\WebSocket;

class Server extends \Swoole\Http\Server
{
    /**
     * @param mixed $fd
     * @param mixed $data
     * @param mixed|null $opcode
     * @param mixed|null $flags
     * @return mixed
     */
    public function push($fd, $data, $opcode = null, $flags = null) {}

    /**
     * @param mixed $fd
     * @param mixed|null $code
     * @param mixed|null $reason
     * @return mixed
     */
    public function disconnect($fd, $code = null, $reason = null) {}

    /**
     * @param mixed $fd
     * @return mixed
     */
    public function isEstablished($fd) {}

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
