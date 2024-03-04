<?php 

/** @param resource $result */
#[\Until('8.1')]
function pg_result_error($result) : string|false
{
}
/** @refcount 1 */
#[\Since('8.1')]
function pg_result_error(\PgSql\Result $result) : string|false
{
}