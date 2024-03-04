<?php 

#if !defined(HAVE_DBMAKER) && !defined(HAVE_SOLID) && !defined(HAVE_SOLID_30) &&!defined(HAVE_SOLID_35)
/**
 * @param resource $odbc
 * @return resource|false
 */
function odbc_tableprivileges($odbc, ?string $catalog, string $schema, string $table)
{
}