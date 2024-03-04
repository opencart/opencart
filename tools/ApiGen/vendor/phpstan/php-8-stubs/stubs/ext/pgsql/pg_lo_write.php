<?php 

/** @param resource $lob */
#[\Until('8.1')]
function pg_lo_write($lob, string $data, ?int $length = null) : int|false
{
}
#[\Since('8.1')]
function pg_lo_write(\PgSql\Lob $lob, string $data, ?int $length = null) : int|false
{
}