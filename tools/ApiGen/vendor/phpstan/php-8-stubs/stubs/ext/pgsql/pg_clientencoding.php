<?php 

/**
 * @param resource|null $connection
 * @alias pg_client_encoding
 * @deprecated
 */
#[\Until('8.1')]
function pg_clientencoding($connection = null) : string
{
}
/**
 * @alias pg_client_encoding
 * @deprecated
 */
#[\Since('8.1')]
function pg_clientencoding(?\PgSql\Connection $connection = null) : string
{
}