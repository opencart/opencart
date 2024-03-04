<?php 

#[\Until('8.1')]
function pspell_check(int $dictionary, string $word) : bool
{
}
#[\Since('8.1')]
function pspell_check(\PSpell\Dictionary $dictionary, string $word) : bool
{
}