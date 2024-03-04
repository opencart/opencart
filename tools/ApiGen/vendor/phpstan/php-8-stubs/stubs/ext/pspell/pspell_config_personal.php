<?php 

#[\Until('8.1')]
function pspell_config_personal(int $config, string $filename) : bool
{
}
#[\Since('8.1')]
function pspell_config_personal(\PSpell\Config $config, string $filename) : bool
{
}