<?php 

/** @generate-function-entries */
/** @return resource|false */
#[\Until('8.1')]
function pg_connect(string $connection_string, int $flags = 0)
{
}
#[\Since('8.1')]
function pg_connect(string $connection_string, int $flags = 0) : \PgSql\Connection|false
{
}