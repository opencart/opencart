<?php 

/**
 * @param resource $result
 * @param string|int $row
 * @alias pg_fetch_result
 * @deprecated
 */
#[\Until('8.1')]
function pg_result($result, $row, string|int $field = UNKNOWN) : string|false|null
{
}
/**
 * @param string|int $row
 * @alias pg_fetch_result
 * @deprecated
 */
#[\Since('8.1')]
function pg_result(\PgSql\Result $result, $row, string|int $field = UNKNOWN) : string|false|null
{
}