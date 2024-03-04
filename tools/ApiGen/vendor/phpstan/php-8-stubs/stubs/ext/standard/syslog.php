<?php 

#[\Until('8.2')]
function syslog(int $priority, string $message) : bool
{
}
#[\Since('8.2')]
function syslog(int $priority, string $message) : true
{
}