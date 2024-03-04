<?php 

#[\Until('8.1')]
function pspell_config_runtogether(int $config, bool $allow) : bool
{
}
#[\Since('8.1')]
function pspell_config_runtogether(\PSpell\Config $config, bool $allow) : bool
{
}