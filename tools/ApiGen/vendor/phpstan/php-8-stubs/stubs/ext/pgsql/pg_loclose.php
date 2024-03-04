<?php 

/**
 * @param resource $lob
 * @alias pg_lo_close
 * @deprecated
 */
#[\Until('8.1')]
function pg_loclose($lob) : bool
{
}
/**
 * @alias pg_lo_close
 * @deprecated
 */
#[\Since('8.1')]
function pg_loclose(\PgSql\Lob $lob) : bool
{
}