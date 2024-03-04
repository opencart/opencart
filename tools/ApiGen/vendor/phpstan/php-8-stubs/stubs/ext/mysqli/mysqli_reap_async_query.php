<?php 

#if defined(MYSQLI_USE_MYSQLND)
function mysqli_reap_async_query(\mysqli $mysql) : \mysqli_result|bool
{
}