<?php 

/**
 * @param resource $ldap
 * @alias ldap_unbind
 */
#[\Until('8.1')]
function ldap_close($ldap) : bool
{
}
/** @alias ldap_unbind */
#[\Since('8.1')]
function ldap_close(\LDAP\Connection $ldap) : bool
{
}