<?php 

/** @param resource $bz */
#[\Until('8.1')]
function bzerror($bz) : array|false
{
}
/**
 * @param resource $bz
 * @return array<string, int|string>
 * @refcount 1
 */
#[\Since('8.1')]
function bzerror($bz) : array
{
}