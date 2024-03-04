<?php 

/**
 * @param int $error_code
 * @param string $error_message
 * @return resource|false
 */
function pfsockopen(string $hostname, int $port = -1, &$error_code = null, &$error_message = null, ?float $timeout = null)
{
}