<?php 

/** @param resource $lob */
#[\Until('8.1')]
function pg_lo_truncate($lob, int $size) : bool
{
}
#[\Since('8.1')]
function pg_lo_truncate(\PgSql\Lob $lob, int $size) : bool
{
}