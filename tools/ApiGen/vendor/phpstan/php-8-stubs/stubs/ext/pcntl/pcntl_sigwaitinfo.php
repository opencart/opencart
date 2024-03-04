<?php 

#endif
#ifdef HAVE_STRUCT_SIGINFO_T
#if defined(HAVE_SIGWAITINFO) && defined(HAVE_SIGTIMEDWAIT)
/** @param array $info */
function pcntl_sigwaitinfo(array $signals, &$info = []) : int|false
{
}