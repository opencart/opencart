<?php 

final class mysqli_sql_exception extends \RuntimeException
{
    #[\Since('8.1')]
    public function getSqlState() : string
    {
    }
}