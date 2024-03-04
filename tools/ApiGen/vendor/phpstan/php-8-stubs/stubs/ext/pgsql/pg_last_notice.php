<?php 

/** @param resource $connection */
#[\Until('8.1')]
function pg_last_notice($connection, int $mode = PGSQL_NOTICE_LAST) : array|string|bool
{
}
#[\Since('8.1')]
function pg_last_notice(\PgSql\Connection $connection, int $mode = PGSQL_NOTICE_LAST) : array|string|bool
{
}