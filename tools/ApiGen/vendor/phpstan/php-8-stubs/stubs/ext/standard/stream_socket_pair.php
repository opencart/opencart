<?php 

#endif
#if HAVE_SOCKETPAIR
/**
 * @refcount 1
 */
function stream_socket_pair(int $domain, int $type, int $protocol) : array|false
{
}