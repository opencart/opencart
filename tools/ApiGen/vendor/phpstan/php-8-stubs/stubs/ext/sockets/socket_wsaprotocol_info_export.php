<?php 

#ifdef PHP_WIN32
function socket_wsaprotocol_info_export(\Socket $socket, int $process_id) : string|false
{
}