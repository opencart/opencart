<?php 

/**
 * @param string $data
 * @param string $address
 * @param int $port
 */
function socket_recvfrom(\Socket $socket, &$data, int $length, int $flags, &$address, &$port = null) : int|false
{
}