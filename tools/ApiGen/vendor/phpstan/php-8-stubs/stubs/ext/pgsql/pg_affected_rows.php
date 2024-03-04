<?php 

/** @param resource $result */
#[\Until('8.1')]
function pg_affected_rows($result) : int
{
}
#[\Since('8.1')]
function pg_affected_rows(\PgSql\Result $result) : int
{
}