<?php 

#endif
#ifdef HAVE_RFORK
#[\Since('8.1')]
function pcntl_rfork(int $flags, int $signal = 0) : int
{
}