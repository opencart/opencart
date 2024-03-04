<?php 

/**
 * @param resource $result
 * @alias pg_free_result
 * @deprecated
 */
#[\Until('8.1')]
function pg_freeresult($result) : bool
{
}
/**
 * @alias pg_free_result
 * @deprecated
 */
#[\Since('8.1')]
function pg_freeresult(\PgSql\Result $result) : bool
{
}