<?php 

/**
 * @param resource $lob
 * @alias pg_lo_read_all
 * @deprecated
 */
#[\Until('8.1')]
function pg_loreadall($lob) : int
{
}
/**
 * @alias pg_lo_read_all
 * @deprecated
 */
#[\Since('8.1')]
function pg_loreadall(\PgSql\Lob $lob) : int
{
}