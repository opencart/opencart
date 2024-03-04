<?php 

/**
 * @param resource $result
 * @alias pg_num_fields
 * @deprecated
 */
#[\Until('8.1')]
function pg_numfields($result) : int
{
}
/**
 * @alias pg_num_fields
 * @deprecated
 */
#[\Since('8.1')]
function pg_numfields(\PgSql\Result $result) : int
{
}