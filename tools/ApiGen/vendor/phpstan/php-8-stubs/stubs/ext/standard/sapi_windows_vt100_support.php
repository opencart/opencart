<?php 

#ifdef PHP_WIN32
/** @param resource $stream */
function sapi_windows_vt100_support($stream, ?bool $enable = null) : bool
{
}