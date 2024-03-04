<?php 

/**
 * @param int $queued_messages
 * @refcount 1
 */
function curl_multi_info_read(\CurlMultiHandle $multi_handle, &$queued_messages = null) : array|false
{
}