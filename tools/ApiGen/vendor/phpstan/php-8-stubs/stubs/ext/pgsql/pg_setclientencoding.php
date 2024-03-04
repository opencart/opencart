<?php 

/**
 * @param resource|string $connection
 * @alias pg_set_client_encoding
 * @deprecated
 */
#[\Until('8.1')]
function pg_setclientencoding($connection, string $encoding = UNKNOWN) : int
{
}
/**
 * @param PgSql\Connection|string $connection
 * @alias pg_set_client_encoding
 * @deprecated
 */
#[\Since('8.1')]
function pg_setclientencoding($connection, string $encoding = UNKNOWN) : int
{
}