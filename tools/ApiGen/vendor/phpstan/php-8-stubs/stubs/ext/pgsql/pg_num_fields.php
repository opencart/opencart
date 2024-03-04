<?php 

/** @param resource $result */
#[\Until('8.1')]
function pg_num_fields($result) : int
{
}
#[\Since('8.1')]
function pg_num_fields(\PgSql\Result $result) : int
{
}