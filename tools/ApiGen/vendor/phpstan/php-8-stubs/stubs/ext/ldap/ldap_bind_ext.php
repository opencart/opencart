<?php 

/**
 * @param resource $ldap
 * @return resource|false
 */
#[\Until('8.1')]
function ldap_bind_ext($ldap, ?string $dn = null, ?string $password = null, ?array $controls = null)
{
}
#[\Since('8.1')]
function ldap_bind_ext(\LDAP\Connection $ldap, ?string $dn = null, ?string $password = null, ?array $controls = null) : \LDAP\Result|false
{
}