<?php 

/** @param resource $connection */
#[\Until('8.1')]
function pg_consume_input($connection) : bool
{
}
#[\Since('8.1')]
function pg_consume_input(\PgSql\Connection $connection) : bool
{
}