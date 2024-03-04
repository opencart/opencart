<?php 

#[\Until('8.1')]
function pspell_add_to_personal(int $dictionary, string $word) : bool
{
}
#[\Since('8.1')]
function pspell_add_to_personal(\PSpell\Dictionary $dictionary, string $word) : bool
{
}