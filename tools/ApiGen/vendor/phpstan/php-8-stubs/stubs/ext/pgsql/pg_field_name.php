<?php 

/** @param resource $result */
#[\Until('8.1')]
function pg_field_name($result, int $field) : string
{
}
/** @refcount 1 */
#[\Since('8.1')]
function pg_field_name(\PgSql\Result $result, int $field) : string
{
}