<?php 

/** @generate-function-entries */
#[\Until('8.1')]
function pspell_new(string $language, string $spelling = "", string $jargon = "", string $encoding = "", int $mode = 0) : int|false
{
}
#[\Since('8.1')]
function pspell_new(string $language, string $spelling = "", string $jargon = "", string $encoding = "", int $mode = 0) : \PSpell\Dictionary|false
{
}