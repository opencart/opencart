<?php 

#if defined(MYSQLI_USE_MYSQLND)
/**
 * @refcount 1
 */
function mysqli_fetch_all(\mysqli_result $result, int $mode = MYSQLI_NUM) : array
{
}