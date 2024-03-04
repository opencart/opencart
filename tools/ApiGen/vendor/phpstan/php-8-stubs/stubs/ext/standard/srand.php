<?php 

/** @alias mt_srand */
#[\Until('8.1')]
function srand(int $seed = 0, int $mode = MT_RAND_MT19937) : void
{
}
/** @alias mt_srand */
#[\Since('8.1')]
function srand(int $seed = UNKNOWN, int $mode = MT_RAND_MT19937) : void
{
}