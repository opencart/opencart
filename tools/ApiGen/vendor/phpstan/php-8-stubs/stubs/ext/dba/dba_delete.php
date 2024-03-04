<?php 

/**
 * @param string|array $key
 * @param resource $dba
 */
#[\Until('8.2')]
function dba_delete($key, $dba) : bool
{
}
/** @param resource $dba */
#[\Since('8.2')]
function dba_delete(string|array $key, $dba) : bool
{
}