<?php 

/** @param resource $result */
#[\Until('8.1')]
function pg_result_error_field($result, int $field_code) : string|false|null
{
}
/** @refcount 1 */
#[\Since('8.1')]
function pg_result_error_field(\PgSql\Result $result, int $field_code) : string|false|null
{
}