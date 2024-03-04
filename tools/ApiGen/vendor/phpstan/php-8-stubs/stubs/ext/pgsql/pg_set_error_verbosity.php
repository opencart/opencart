<?php 

/** @param resource|int $connection */
#[\Until('8.1')]
function pg_set_error_verbosity($connection, int $verbosity = UNKNOWN) : int|false
{
}
/** @param PgSql\Connection|int $connection */
#[\Since('8.1')]
function pg_set_error_verbosity($connection, int $verbosity = UNKNOWN) : int|false
{
}