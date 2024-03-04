<?php 

/**
 * @param resource $result
 * @alias pg_affected_rows
 * @deprecated
 */
#[\Until('8.1')]
function pg_cmdtuples($result) : int
{
}
/**
 * @alias pg_affected_rows
 * @deprecated
 */
#[\Since('8.1')]
function pg_cmdtuples(\PgSql\Result $result) : int
{
}