<?php 

/** @param resource $connection */
#[\Until('8.1')]
function pg_get_pid($connection) : int
{
}
#[\Since('8.1')]
function pg_get_pid(\PgSql\Connection $connection) : int
{
}