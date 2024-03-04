<?php 

/* mt_rand.c */
#[\Until('8.1')]
function mt_srand(int $seed = 0, int $mode = MT_RAND_MT19937) : void
{
}
/* mt_rand.c */
#[\Since('8.1')]
function mt_srand(int $seed = UNKNOWN, int $mode = MT_RAND_MT19937) : void
{
}