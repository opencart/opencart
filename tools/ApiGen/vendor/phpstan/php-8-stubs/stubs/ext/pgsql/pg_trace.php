<?php 

/** @param resource|null $connection */
#[\Until('8.1')]
function pg_trace(string $filename, string $mode = "w", $connection = null) : bool
{
}
#[\Since('8.1')]
function pg_trace(string $filename, string $mode = "w", ?\PgSql\Connection $connection = null) : bool
{
}