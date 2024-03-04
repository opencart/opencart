<?php 

/** @param resource $connection */
#[\Until('8.1')]
function pg_send_execute($connection, string $statement_name, array $params) : int|bool
{
}
#[\Since('8.1')]
function pg_send_execute(\PgSql\Connection $connection, string $statement_name, array $params) : int|bool
{
}