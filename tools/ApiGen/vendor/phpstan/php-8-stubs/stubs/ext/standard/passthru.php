<?php 

/**
 * @param int $result_code
 */
#[\Until('8.2')]
function passthru(string $command, &$result_code = null) : ?bool
{
}
/** @param int $result_code */
#[\Since('8.2')]
function passthru(string $command, &$result_code = null) : false|null
{
}