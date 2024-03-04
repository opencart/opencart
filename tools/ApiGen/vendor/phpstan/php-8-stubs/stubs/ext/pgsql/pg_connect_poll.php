<?php 

/** @param resource $connection */
#[\Until('8.1')]
function pg_connect_poll($connection) : int
{
}
#[\Since('8.1')]
function pg_connect_poll(\PgSql\Connection $connection) : int
{
}