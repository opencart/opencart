<?php 

/** @alias mysqli_stmt_execute */
#[\Until('8.1')]
function mysqli_execute(\mysqli_stmt $statement) : bool
{
}
/** @alias mysqli_stmt_execute */
#[\Since('8.1')]
function mysqli_execute(\mysqli_stmt $statement, ?array $params = null) : bool
{
}