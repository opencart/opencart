<?php 

/** @param resource $ldap */
#[\Until('8.1')]
function ldap_bind($ldap, ?string $dn = null, ?string $password = null) : bool
{
}
#[\Since('8.1')]
function ldap_bind(\LDAP\Connection $ldap, ?string $dn = null, ?string $password = null) : bool
{
}