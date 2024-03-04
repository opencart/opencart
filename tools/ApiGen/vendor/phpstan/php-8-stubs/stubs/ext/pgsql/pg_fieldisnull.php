<?php 

/**
 * @param resource $result
 * @param string|int $row
 * @alias pg_field_is_null
 * @deprecated
 */
#[\Until('8.1')]
function pg_fieldisnull($result, $row, string|int $field = UNKNOWN) : int|false
{
}
/**
 * @param string|int $row
 * @alias pg_field_is_null
 * @deprecated
 */
#[\Since('8.1')]
function pg_fieldisnull(\PgSql\Result $result, $row, string|int $field = UNKNOWN) : int|false
{
}