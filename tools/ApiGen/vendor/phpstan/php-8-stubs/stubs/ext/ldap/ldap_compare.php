<?php 

/** @param resource $ldap */
#[\Until('8.1')]
function ldap_compare($ldap, string $dn, string $attribute, string $value, ?array $controls = null) : bool|int
{
}
#[\Since('8.1')]
function ldap_compare(\LDAP\Connection $ldap, string $dn, string $attribute, string $value, ?array $controls = null) : bool|int
{
}