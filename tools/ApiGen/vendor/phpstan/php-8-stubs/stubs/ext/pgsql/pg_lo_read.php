<?php 

/** @param resource $lob */
#[\Until('8.1')]
function pg_lo_read($lob, int $length = 8192) : string|false
{
}
/** @refcount 1 */
#[\Since('8.1')]
function pg_lo_read(\PgSql\Lob $lob, int $length = 8192) : string|false
{
}