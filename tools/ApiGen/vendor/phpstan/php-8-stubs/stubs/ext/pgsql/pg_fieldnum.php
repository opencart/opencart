<?php 

/**
 * @param resource $result
 * @alias pg_field_num
 * @deprecated
 */
#[\Until('8.1')]
function pg_fieldnum($result, string $field) : int
{
}
/**
 * @alias pg_field_num
 * @deprecated
 */
#[\Since('8.1')]
function pg_fieldnum(\PgSql\Result $result, string $field) : int
{
}