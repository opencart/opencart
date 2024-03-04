<?php 

/** @param resource $connection */
#[\Until('8.1')]
function pg_convert($connection, string $table_name, array $values, int $flags = 0) : array|false
{
}
/**
 * @return array<string, mixed>|false
 * @refcount 1
 */
#[\Since('8.1')]
function pg_convert(\PgSql\Connection $connection, string $table_name, array $values, int $flags = 0) : array|false
{
}