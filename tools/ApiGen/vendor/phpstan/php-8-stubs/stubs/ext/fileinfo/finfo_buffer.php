<?php 

/**
 * @param resource $finfo
 * @param resource|null $context
 */
#[\Until('8.1')]
function finfo_buffer($finfo, string $string, int $flags = FILEINFO_NONE, $context = null) : string|false
{
}
/**
 * @param resource|null $context
 * @refcount 1
 */
#[\Since('8.1')]
function finfo_buffer(\finfo $finfo, string $string, int $flags = FILEINFO_NONE, $context = null) : string|false
{
}