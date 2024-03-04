<?php 

/** @param resource $result */
#[\Until('8.1')]
function pg_free_result($result) : bool
{
}
#[\Since('8.1')]
function pg_free_result(\PgSql\Result $result) : bool
{
}