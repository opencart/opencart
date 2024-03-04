<?php 

#[\Until('8.1')]
function html_entity_decode(string $string, int $flags = ENT_COMPAT, ?string $encoding = null) : string
{
}
#[\Since('8.1')]
function html_entity_decode(string $string, int $flags = ENT_QUOTES | ENT_SUBSTITUTE | ENT_HTML401, ?string $encoding = null) : string
{
}