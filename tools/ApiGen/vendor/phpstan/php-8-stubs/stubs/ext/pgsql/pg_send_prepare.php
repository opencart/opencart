<?php 

/** @param resource $connection */
#[\Until('8.1')]
function pg_send_prepare($connection, string $statement_name, string $query) : int|bool
{
}
#[\Since('8.1')]
function pg_send_prepare(\PgSql\Connection $connection, string $statement_name, string $query) : int|bool
{
}