<?php 

/**
 * @param resource $result
 */
#[\Until('8.1')]
function pg_fetch_assoc($result, ?int $row = null) : array|false
{
}
/**
 * @return array<int|string, string|null>|false
 * @refcount 1
 */
#[\Since('8.1')]
function pg_fetch_assoc(\PgSql\Result $result, ?int $row = null) : array|false
{
}