<?php 

/** @param resource|string $connection */
#[\Until('8.1')]
function pg_set_client_encoding($connection, string $encoding = UNKNOWN) : int
{
}
/** @param PgSql\Connection|string $connection */
#[\Since('8.1')]
function pg_set_client_encoding($connection, string $encoding = UNKNOWN) : int
{
}