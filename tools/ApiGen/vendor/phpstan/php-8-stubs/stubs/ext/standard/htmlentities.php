<?php 

#[\Until('8.1')]
function htmlentities(string $string, int $flags = ENT_COMPAT, ?string $encoding = null, bool $double_encode = true) : string
{
}
/** @refcount 1 */
#[\Since('8.1')]
function htmlentities(string $string, int $flags = ENT_QUOTES | ENT_SUBSTITUTE | ENT_HTML401, ?string $encoding = null, bool $double_encode = true) : string
{
}