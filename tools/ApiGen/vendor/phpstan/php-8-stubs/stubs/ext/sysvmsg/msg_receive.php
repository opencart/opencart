<?php 

/**
 * @param int $received_message_type
 * @param int $error_code
 */
function msg_receive(\SysvMessageQueue $queue, int $desired_message_type, &$received_message_type, int $max_message_size, mixed &$message, bool $unserialize = true, int $flags = 0, &$error_code = null) : bool
{
}