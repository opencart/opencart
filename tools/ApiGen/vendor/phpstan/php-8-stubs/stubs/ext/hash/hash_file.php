<?php 

#[\Until('8.1')]
function hash_file(string $algo, string $filename, bool $binary = false) : string|false
{
}
/** @refcount 1 */
#[\Since('8.1')]
function hash_file(string $algo, string $filename, bool $binary = false, array $options = []) : string|false
{
}