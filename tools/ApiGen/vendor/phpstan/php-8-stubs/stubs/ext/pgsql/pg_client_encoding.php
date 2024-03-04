<?php 

/** @param resource|null $connection */
#[\Until('8.1')]
function pg_client_encoding($connection = null) : string
{
}
#[\Since('8.1')]
function pg_client_encoding(?\PgSql\Connection $connection = null) : string
{
}