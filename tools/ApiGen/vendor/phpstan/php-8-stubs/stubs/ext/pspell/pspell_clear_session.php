<?php 

#[\Until('8.1')]
function pspell_clear_session(int $dictionary) : bool
{
}
#[\Since('8.1')]
function pspell_clear_session(\PSpell\Dictionary $dictionary) : bool
{
}