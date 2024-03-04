<?php 

/** @param resource $connection */
#[\Until('8.1')]
function pg_copy_from($connection, string $table_name, array $rows, string $separator = "\t", string $null_as = "\\\\N") : bool
{
}
#[\Since('8.1')]
function pg_copy_from(\PgSql\Connection $connection, string $table_name, array $rows, string $separator = "\t", string $null_as = "\\\\N") : bool
{
}