<?php 

/**
 * @param resource $result
 * @alias pg_field_name
 * @deprecated
 */
#[\Until('8.1')]
function pg_fieldname($result, int $field) : string
{
}
/**
 * @alias pg_field_name
 * @deprecated
 */
#[\Since('8.1')]
function pg_fieldname(\PgSql\Result $result, int $field) : string
{
}