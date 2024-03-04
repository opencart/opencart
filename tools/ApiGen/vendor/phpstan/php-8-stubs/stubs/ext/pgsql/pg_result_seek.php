<?php 

/** @param resource $result */
#[\Until('8.1')]
function pg_result_seek($result, int $row) : bool
{
}
#[\Since('8.1')]
function pg_result_seek(\PgSql\Result $result, int $row) : bool
{
}