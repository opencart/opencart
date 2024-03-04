<?php 

/**
 * @param resource $result
 * @alias pg_field_size
 * @deprecated
 */
#[\Until('8.1')]
function pg_fieldsize($result, int $field) : int
{
}
/**
 * @alias pg_field_size
 * @deprecated
 */
#[\Since('8.1')]
function pg_fieldsize(\PgSql\Result $result, int $field) : int
{
}