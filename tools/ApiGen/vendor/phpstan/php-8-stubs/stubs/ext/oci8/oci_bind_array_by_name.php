<?php 

/**
 * @param resource $statement
 * @param array $var
 */
function oci_bind_array_by_name($statement, string $param, &$var, int $max_array_length, int $max_item_length = -1, int $type = SQLT_AFC) : bool
{
}