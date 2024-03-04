<?php 

/** @param resource $result */
#[\Until('8.1')]
function pg_field_size($result, int $field) : int
{
}
#[\Since('8.1')]
function pg_field_size(\PgSql\Result $result, int $field) : int
{
}