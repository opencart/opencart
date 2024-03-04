<?php 

/**
 * @param int $error_code
 * @param string $error_message
 * @param resource|null $context
 * @return resource|false
 */
function stream_socket_server(string $address, &$error_code = null, &$error_message = null, int $flags = STREAM_SERVER_BIND | STREAM_SERVER_LISTEN, $context = null)
{
}