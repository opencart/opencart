<?php 

/**
 * @param callable|object $open
 * @param callable|bool $close
 */
function session_set_save_handler($open, $close = UNKNOWN, callable $read = UNKNOWN, callable $write = UNKNOWN, callable $destroy = UNKNOWN, callable $gc = UNKNOWN, callable $create_sid = UNKNOWN, callable $validate_sid = UNKNOWN, callable $update_timestamp = UNKNOWN) : bool
{
}