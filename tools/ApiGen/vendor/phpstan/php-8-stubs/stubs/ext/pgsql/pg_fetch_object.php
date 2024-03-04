<?php 

/** @param resource $result */
#[\Until('8.1')]
function pg_fetch_object($result, ?int $row = null, string $class = "stdClass", array $constructor_args = []) : object|false
{
}
/** @refcount 1 */
#[\Since('8.1')]
function pg_fetch_object(\PgSql\Result $result, ?int $row = null, string $class = "stdClass", array $constructor_args = []) : object|false
{
}