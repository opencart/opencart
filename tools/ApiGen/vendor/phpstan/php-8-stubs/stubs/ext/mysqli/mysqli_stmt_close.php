<?php 

#[\Until('8.2')]
function mysqli_stmt_close(\mysqli_stmt $statement) : bool
{
}
#[\Since('8.2')]
function mysqli_stmt_close(\mysqli_stmt $statement) : true
{
}