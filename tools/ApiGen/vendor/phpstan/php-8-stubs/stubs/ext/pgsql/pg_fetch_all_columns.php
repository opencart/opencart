<?php 

/** @param resource $result */
#[\Until('8.1')]
function pg_fetch_all_columns($result, int $field = 0) : array
{
}
/**
 * @return array<int, string|null>
 * @refcount 1
 */
#[\Since('8.1')]
function pg_fetch_all_columns(\PgSql\Result $result, int $field = 0) : array
{
}