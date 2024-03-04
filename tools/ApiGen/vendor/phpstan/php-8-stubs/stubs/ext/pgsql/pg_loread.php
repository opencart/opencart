<?php 

/**
 * @param resource $lob
 * @alias pg_lo_read
 * @deprecated
 */
#[\Until('8.1')]
function pg_loread($lob, int $length = 8192) : string|false
{
}
/**
 * @alias pg_lo_read
 * @deprecated
 */
#[\Since('8.1')]
function pg_loread(\PgSql\Lob $lob, int $length = 8192) : string|false
{
}