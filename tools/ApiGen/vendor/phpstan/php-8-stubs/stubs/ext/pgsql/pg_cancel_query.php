<?php 

/** @param resource $connection */
#[\Until('8.1')]
function pg_cancel_query($connection) : bool
{
}
#[\Since('8.1')]
function pg_cancel_query(\PgSql\Connection $connection) : bool
{
}