<?php 

/** @param resource $connection */
#[\Until('8.1')]
function pg_flush($connection) : int|bool
{
}
#[\Since('8.1')]
function pg_flush(\PgSql\Connection $connection) : int|bool
{
}