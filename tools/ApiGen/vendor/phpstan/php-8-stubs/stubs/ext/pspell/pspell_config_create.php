<?php 

#[\Until('8.1')]
function pspell_config_create(string $language, string $spelling = "", string $jargon = "", string $encoding = "") : int
{
}
#[\Since('8.1')]
function pspell_config_create(string $language, string $spelling = "", string $jargon = "", string $encoding = "") : \PSpell\Config
{
}