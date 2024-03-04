<?php 

/**
 * @param resource $result
 * @param string|int $row
 */
#[\Until('8.1')]
function pg_field_prtlen($result, $row, string|int $field = UNKNOWN) : int|false
{
}
/** @param string|int $row */
#[\Since('8.1')]
function pg_field_prtlen(\PgSql\Result $result, $row, string|int $field = UNKNOWN) : int|false
{
}