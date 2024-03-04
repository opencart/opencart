<?php 

/**
 * @param string|array $key
 * @param resource $dba
 */
#[\Until('8.2')]
function dba_replace($key, string $value, $dba) : bool
{
}
/** @param resource $dba */
#[\Since('8.2')]
function dba_replace(string|array $key, string $value, $dba) : bool
{
}