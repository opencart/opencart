<?php 

/** @param resource $lob */
#[\Until('8.1')]
function pg_lo_seek($lob, int $offset, int $whence = SEEK_CUR) : bool
{
}
#[\Since('8.1')]
function pg_lo_seek(\PgSql\Lob $lob, int $offset, int $whence = SEEK_CUR) : bool
{
}