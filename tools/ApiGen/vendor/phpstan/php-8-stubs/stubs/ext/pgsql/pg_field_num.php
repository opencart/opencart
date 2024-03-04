<?php 

/** @param resource $result */
#[\Until('8.1')]
function pg_field_num($result, string $field) : int
{
}
#[\Since('8.1')]
function pg_field_num(\PgSql\Result $result, string $field) : int
{
}