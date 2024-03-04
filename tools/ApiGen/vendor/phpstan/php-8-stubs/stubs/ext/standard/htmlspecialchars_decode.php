<?php 

#[\Until('8.1')]
function htmlspecialchars_decode(string $string, int $flags = ENT_COMPAT) : string
{
}
#[\Since('8.1')]
function htmlspecialchars_decode(string $string, int $flags = ENT_QUOTES | ENT_SUBSTITUTE | ENT_HTML401) : string
{
}