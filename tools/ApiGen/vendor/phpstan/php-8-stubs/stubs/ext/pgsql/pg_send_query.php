<?php 

/** @param resource $connection */
#[\Until('8.1')]
function pg_send_query($connection, string $query) : int|bool
{
}
#[\Since('8.1')]
function pg_send_query(\PgSql\Connection $connection, string $query) : int|bool
{
}