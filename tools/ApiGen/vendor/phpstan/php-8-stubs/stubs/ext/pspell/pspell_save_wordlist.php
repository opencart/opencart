<?php 

#[\Until('8.1')]
function pspell_save_wordlist(int $dictionary) : bool
{
}
#[\Since('8.1')]
function pspell_save_wordlist(\PSpell\Dictionary $dictionary) : bool
{
}