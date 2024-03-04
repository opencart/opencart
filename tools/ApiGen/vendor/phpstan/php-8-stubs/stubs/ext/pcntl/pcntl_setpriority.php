<?php 

#endif
#ifdef HAVE_SETPRIORITY
function pcntl_setpriority(int $priority, ?int $process_id = null, int $mode = PRIO_PROCESS) : bool
{
}