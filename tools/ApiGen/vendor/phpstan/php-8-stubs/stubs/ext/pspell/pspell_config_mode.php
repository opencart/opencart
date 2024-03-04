<?php 

#[\Until('8.1')]
function pspell_config_mode(int $config, int $mode) : bool
{
}
#[\Since('8.1')]
function pspell_config_mode(\PSpell\Config $config, int $mode) : bool
{
}