<?php 

/** @param resource $bz */
#[\Until('8.1')]
function bzerrstr($bz) : string|false
{
}
/** @param resource $bz */
#[\Since('8.1')]
function bzerrstr($bz) : string
{
}