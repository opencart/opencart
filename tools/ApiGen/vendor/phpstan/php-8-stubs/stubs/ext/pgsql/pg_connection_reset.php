<?php 

/** @param resource $connection */
#[\Until('8.1')]
function pg_connection_reset($connection) : bool
{
}
#[\Since('8.1')]
function pg_connection_reset(\PgSql\Connection $connection) : bool
{
}