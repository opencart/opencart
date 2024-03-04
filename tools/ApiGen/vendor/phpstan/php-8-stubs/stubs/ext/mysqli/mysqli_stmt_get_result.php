<?php 

#if defined(MYSQLI_USE_MYSQLND)
function mysqli_stmt_get_result(\mysqli_stmt $statement) : \mysqli_result|false
{
}