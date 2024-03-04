<?php
/**
 * Returns FPM status info array
 * @since 7.3
 * @return array|false
 */
function fpm_get_status(): array|false {}

/**
 * This function flushes all response data to the client and finishes the request.
 * This allows for time consuming tasks to be performed without leaving the connection to the client open.
 * @return bool Returns TRUE on success or FALSE on failure.
 * @link https://php.net/manual/en/install.fpm.php
 * @since 5.3.3
 */
function fastcgi_finish_request() {};
