<?php 

/** @param resource|string $connection */
#[\Until('8.1')]
function pg_put_line($connection, string $query = UNKNOWN) : bool
{
}
/** @param PgSql\Connection|string $connection */
#[\Since('8.1')]
function pg_put_line($connection, string $query = UNKNOWN) : bool
{
}