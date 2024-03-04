<?php 

/** @param resource $result */
#[\Until('8.1')]
function pg_field_type_oid($result, int $field) : string|int
{
}
/** @refcount 1 */
#[\Since('8.1')]
function pg_field_type_oid(\PgSql\Result $result, int $field) : string|int
{
}