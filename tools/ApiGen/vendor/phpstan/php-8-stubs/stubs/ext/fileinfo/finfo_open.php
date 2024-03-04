<?php 

/** @return resource|false */
#[\Until('8.1')]
function finfo_open(int $flags = FILEINFO_NONE, ?string $magic_database = null)
{
}
/** @refcount 1 */
#[\Since('8.1')]
function finfo_open(int $flags = FILEINFO_NONE, ?string $magic_database = null) : \finfo|false
{
}