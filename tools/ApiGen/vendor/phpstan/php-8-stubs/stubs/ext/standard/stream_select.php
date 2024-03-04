<?php 

/* streamsfuncs.c */
#[\Until('8.1')]
function stream_select(?array &$read, ?array &$write, ?array &$except, ?int $seconds, int $microseconds = 0) : int|false
{
}
/* streamsfuncs.c */
#[\Since('8.1')]
function stream_select(?array &$read, ?array &$write, ?array &$except, ?int $seconds, ?int $microseconds = null) : int|false
{
}