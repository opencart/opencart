<?php 

#[\Until('8.1')]
function pspell_config_dict_dir(int $config, string $directory) : bool
{
}
#[\Since('8.1')]
function pspell_config_dict_dir(\PSpell\Config $config, string $directory) : bool
{
}