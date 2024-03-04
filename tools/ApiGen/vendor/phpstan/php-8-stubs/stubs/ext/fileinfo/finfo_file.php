<?php 

/**
 * @param resource $finfo
 * @param resource|null $context
 */
#[\Until('8.1')]
function finfo_file($finfo, string $filename, int $flags = FILEINFO_NONE, $context = null) : string|false
{
}
// TODO make return type void
/**
 * @param resource|null $context
 * @refcount 1
 */
#[\Since('8.1')]
function finfo_file(\finfo $finfo, string $filename, int $flags = FILEINFO_NONE, $context = null) : string|false
{
}