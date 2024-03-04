<?php 

/**
 * @param resource|null $connection
 * @alias pg_last_error
 * @deprecated
 */
#[\Until('8.1')]
function pg_errormessage($connection = null) : string
{
}
/**
 * @alias pg_last_error
 * @deprecated
 */
#[\Since('8.1')]
function pg_errormessage(?\PgSql\Connection $connection = null) : string
{
}