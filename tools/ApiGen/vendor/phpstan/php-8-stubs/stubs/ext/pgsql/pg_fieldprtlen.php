<?php 

/**
 * @param resource $result
 * @param string|int $row
 * @alias pg_field_prtlen
 * @deprecated
 */
#[\Until('8.1')]
function pg_fieldprtlen($result, $row, string|int $field = UNKNOWN) : int|false
{
}
/**
 * @param string|int $row
 * @alias pg_field_prtlen
 * @deprecated
 */
#[\Since('8.1')]
function pg_fieldprtlen(\PgSql\Result $result, $row, string|int $field = UNKNOWN) : int|false
{
}