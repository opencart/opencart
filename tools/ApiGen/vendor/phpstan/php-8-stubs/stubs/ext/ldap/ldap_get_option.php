<?php 

/**
 * @param resource $ldap
 * @param array|string|int $value
 */
#[\Until('8.1')]
function ldap_get_option($ldap, int $option, &$value = null) : bool
{
}
/** @param array|string|int $value */
#[\Since('8.1')]
function ldap_get_option(\LDAP\Connection $ldap, int $option, &$value = null) : bool
{
}