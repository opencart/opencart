<?php 

/**
 * @param resource $result
 * @alias pg_num_rows
 * @deprecated
 */
#[\Until('8.1')]
function pg_numrows($result) : int
{
}
/**
 * @alias pg_num_rows
 * @deprecated
 */
#[\Since('8.1')]
function pg_numrows(\PgSql\Result $result) : int
{
}