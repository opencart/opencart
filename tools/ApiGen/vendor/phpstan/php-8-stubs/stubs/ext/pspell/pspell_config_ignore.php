<?php 

#[\Until('8.1')]
function pspell_config_ignore(int $config, int $min_length) : bool
{
}
#[\Since('8.1')]
function pspell_config_ignore(\PSpell\Config $config, int $min_length) : bool
{
}