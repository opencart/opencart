<?php 

/** @param resource $lob */
#[\Until('8.1')]
function pg_lo_tell($lob) : int
{
}
#[\Since('8.1')]
function pg_lo_tell(\PgSql\Lob $lob) : int
{
}