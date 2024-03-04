<?php 

#[\Until('8.2')]
function mysqli_close(\mysqli $mysql) : bool
{
}
#[\Since('8.2')]
function mysqli_close(\mysqli $mysql) : true
{
}