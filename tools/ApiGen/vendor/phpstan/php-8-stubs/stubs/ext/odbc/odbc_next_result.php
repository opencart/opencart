<?php 

#if !defined(HAVE_SOLID) && !defined(HAVE_SOLID_30)
/** @param resource $statement */
function odbc_next_result($statement) : bool
{
}