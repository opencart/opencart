<?php 

/** @param resource $result */
#[\Until('8.1')]
function pg_field_table($result, int $field, bool $oid_only = false) : string|int|false
{
}
#[\Since('8.1')]
function pg_field_table(\PgSql\Result $result, int $field, bool $oid_only = false) : string|int|false
{
}