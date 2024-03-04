<?php 

/**
 * @param resource $stream
 * @param resource|null $session_stream
 */
function stream_socket_enable_crypto($stream, bool $enable, ?int $crypto_method = null, $session_stream = null) : int|bool
{
}