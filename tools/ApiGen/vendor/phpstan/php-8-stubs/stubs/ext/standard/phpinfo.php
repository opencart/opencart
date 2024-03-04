<?php 

#[\Until('8.2')]
function phpinfo(int $flags = INFO_ALL) : bool
{
}
/* info.c */
#[\Since('8.2')]
function phpinfo(int $flags = INFO_ALL) : true
{
}