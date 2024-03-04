<?php 

/**
 * @param resource $statement
 * @param array $output
 * @alias oci_fetch_all
 * @deprecated
 */
function ocifetchstatement($statement, &$output, int $offset = 0, int $limit = -1, int $flags = OCI_FETCHSTATEMENT_BY_COLUMN | OCI_ASSOC) : int
{
}