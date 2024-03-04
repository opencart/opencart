<?php 

#ifdef HAVE_SOCKETPAIR
/** @param array $pair */
function socket_create_pair(int $domain, int $type, int $protocol, &$pair) : bool
{
}