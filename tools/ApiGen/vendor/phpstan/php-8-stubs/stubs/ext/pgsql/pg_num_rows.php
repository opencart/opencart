<?php 

/** @param resource $result */
#[\Until('8.1')]
function pg_num_rows($result) : int
{
}
#[\Since('8.1')]
function pg_num_rows(\PgSql\Result $result) : int
{
}