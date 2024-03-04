<?php 

#[\Until('8.1')]
function pspell_store_replacement(int $dictionary, string $misspelled, string $correct) : bool
{
}
#[\Since('8.1')]
function pspell_store_replacement(\PSpell\Dictionary $dictionary, string $misspelled, string $correct) : bool
{
}