<?php 

/** @param resource $lob */
#[\Until('8.1')]
function pg_lo_close($lob) : bool
{
}
#[\Since('8.1')]
function pg_lo_close(\PgSql\Lob $lob) : bool
{
}