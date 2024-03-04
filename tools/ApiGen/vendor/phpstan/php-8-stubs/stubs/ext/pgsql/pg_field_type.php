<?php 

/** @param resource $result */
#[\Until('8.1')]
function pg_field_type($result, int $field) : string
{
}
#[\Since('8.1')]
function pg_field_type(\PgSql\Result $result, int $field) : string
{
}