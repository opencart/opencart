<?php 

/** @param resource $connection */
#[\Until('8.1')]
function pg_transaction_status($connection) : int
{
}
#[\Since('8.1')]
function pg_transaction_status(\PgSql\Connection $connection) : int
{
}