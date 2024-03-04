<?php 

#[\Until('8.1')]
function mysqli_stmt_execute(\mysqli_stmt $statement) : bool
{
}
#[\Since('8.1')]
function mysqli_stmt_execute(\mysqli_stmt $statement, ?array $params = null) : bool
{
}