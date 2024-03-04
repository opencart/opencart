<?php 

/** @param resource|null $connection */
#[\Until('8.1')]
function pg_version($connection = null) : array
{
}
/**
 * @return array<string, int|string|null>
 * @refcount 1
 */
#[\Since('8.1')]
function pg_version(?\PgSql\Connection $connection = null) : array
{
}