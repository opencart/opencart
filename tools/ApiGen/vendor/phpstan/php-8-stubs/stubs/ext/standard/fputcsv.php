<?php 

/** @param resource $stream */
#[\Until('8.1')]
function fputcsv($stream, array $fields, string $separator = ",", string $enclosure = "\"", string $escape = "\\") : int|false
{
}
/** @param resource $stream */
#[\Since('8.1')]
function fputcsv($stream, array $fields, string $separator = ",", string $enclosure = "\"", string $escape = "\\", string $eol = "\n") : int|false
{
}