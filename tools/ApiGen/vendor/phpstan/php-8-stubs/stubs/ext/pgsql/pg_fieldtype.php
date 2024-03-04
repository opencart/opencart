<?php 

/**
 * @param resource $result
 * @alias pg_field_type
 * @deprecated
 */
#[\Until('8.1')]
function pg_fieldtype($result, int $field) : string
{
}
/**
 * @alias pg_field_type
 * @deprecated
 */
#[\Since('8.1')]
function pg_fieldtype(\PgSql\Result $result, int $field) : string
{
}