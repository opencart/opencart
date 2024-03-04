<?php 

#[\Until('8.1')]
function pspell_new_config(int $config) : int|false
{
}
#[\Since('8.1')]
function pspell_new_config(\PSpell\Config $config) : \PSpell\Dictionary|false
{
}