<?php 

/** @param resource $bz */
#[\Until('8.1')]
function bzerrno($bz) : int|false
{
}
/** @param resource $bz */
#[\Since('8.1')]
function bzerrno($bz) : int
{
}