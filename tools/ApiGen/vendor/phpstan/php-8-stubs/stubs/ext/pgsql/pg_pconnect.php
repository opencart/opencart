<?php 

/** @return resource|false */
#[\Until('8.1')]
function pg_pconnect(string $connection_string, int $flags = 0)
{
}
#[\Since('8.1')]
function pg_pconnect(string $connection_string, int $flags = 0) : \PgSql\Connection|false
{
}