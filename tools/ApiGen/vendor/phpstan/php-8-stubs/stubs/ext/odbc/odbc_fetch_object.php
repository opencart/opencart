<?php 

#ifdef PHP_ODBC_HAVE_FETCH_HASH
/** @param resource $statement */
function odbc_fetch_object($statement, int $row = -1) : \stdClass|false
{
}