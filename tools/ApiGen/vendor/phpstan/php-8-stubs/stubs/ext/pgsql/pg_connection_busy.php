<?php 

/** @param resource $connection */
#[\Until('8.1')]
function pg_connection_busy($connection) : bool
{
}
#[\Since('8.1')]
function pg_connection_busy(\PgSql\Connection $connection) : bool
{
}