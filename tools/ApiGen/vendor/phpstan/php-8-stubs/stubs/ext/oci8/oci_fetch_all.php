<?php 

/**
 * @param resource $statement
 * @param array $output
 */
function oci_fetch_all($statement, &$output, int $offset = 0, int $limit = -1, int $flags = OCI_FETCHSTATEMENT_BY_COLUMN | OCI_ASSOC) : int
{
}