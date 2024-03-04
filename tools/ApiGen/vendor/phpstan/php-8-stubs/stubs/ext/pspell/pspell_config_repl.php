<?php 

#[\Until('8.1')]
function pspell_config_repl(int $config, string $filename) : bool
{
}
#[\Since('8.1')]
function pspell_config_repl(\PSpell\Config $config, string $filename) : bool
{
}