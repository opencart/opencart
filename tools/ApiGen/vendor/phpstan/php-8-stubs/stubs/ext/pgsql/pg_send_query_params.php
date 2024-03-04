<?php 

/** @param resource $connection */
#[\Until('8.1')]
function pg_send_query_params($connection, string $query, array $params) : int|bool
{
}
#[\Since('8.1')]
function pg_send_query_params(\PgSql\Connection $connection, string $query, array $params) : int|bool
{
}