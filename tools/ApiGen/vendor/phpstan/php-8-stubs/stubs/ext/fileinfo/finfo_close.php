<?php 

/**
 * @param resource $finfo
 */
#[\Until('8.1')]
function finfo_close($finfo) : bool
{
}
#[\Since('8.1')]
function finfo_close(\finfo $finfo) : bool
{
}