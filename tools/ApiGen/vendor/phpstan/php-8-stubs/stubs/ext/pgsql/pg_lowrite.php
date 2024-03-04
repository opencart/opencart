<?php 

/**
 * @param resource $lob
 * @alias pg_lo_write
 * @deprecated
 */
#[\Until('8.1')]
function pg_lowrite($lob, string $data, ?int $length = null) : int|false
{
}
/**
 * @alias pg_lo_write
 * @deprecated
 */
#[\Since('8.1')]
function pg_lowrite(\PgSql\Lob $lob, string $data, ?int $length = null) : int|false
{
}