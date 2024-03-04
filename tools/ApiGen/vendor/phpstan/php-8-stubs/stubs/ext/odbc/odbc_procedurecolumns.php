<?php 

#if !defined(HAVE_SOLID) && !defined(HAVE_SOLID_30) && !defined(HAVE_SOLID_35)
/**
 * @param resource $odbc
 * @return resource|false
 */
function odbc_procedurecolumns($odbc, ?string $catalog = null, ?string $schema = null, ?string $procedure = null, ?string $column = null)
{
}