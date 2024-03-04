<?php 

/**
 * @param int $error_code
 * @param string $error_message
 * @param resource|null $context
 * @return resource|false
 */
function stream_socket_client(string $address, &$error_code = null, &$error_message = null, ?float $timeout = null, int $flags = STREAM_CLIENT_CONNECT, $context = null)
{
}