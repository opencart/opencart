<?php 

#if defined(MYSQLI_USE_MYSQLND)
function mysqli_poll(?array &$read, ?array &$error, array &$reject, int $seconds, int $microseconds = 0) : int|false
{
}