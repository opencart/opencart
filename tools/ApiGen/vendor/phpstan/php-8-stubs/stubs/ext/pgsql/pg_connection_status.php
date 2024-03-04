<?php 

/** @param resource $connection */
#[\Until('8.1')]
function pg_connection_status($connection) : int
{
}
#[\Since('8.1')]
function pg_connection_status(\PgSql\Connection $connection) : int
{
}