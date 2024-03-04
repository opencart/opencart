<?php 

#[\Until('8.1')]
function get_html_translation_table(int $table = HTML_SPECIALCHARS, int $flags = ENT_COMPAT, string $encoding = "UTF-8") : array
{
}
/**
 * @return array<string, string>
 * @refcount 1
 */
#[\Since('8.1')]
function get_html_translation_table(int $table = HTML_SPECIALCHARS, int $flags = ENT_QUOTES | ENT_SUBSTITUTE | ENT_HTML401, string $encoding = "UTF-8") : array
{
}