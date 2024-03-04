<?php 

/** @param resource $lob */
#[\Until('8.1')]
function pg_lo_read_all($lob) : int
{
}
#[\Since('8.1')]
function pg_lo_read_all(\PgSql\Lob $lob) : int
{
}