<?php 

/**
 * @param resource|null $ldap
 * @param array|string|int|bool $value
 */
#[\Until('8.1')]
function ldap_set_option($ldap, int $option, $value) : bool
{
}
/** @param array|string|int|bool $value */
#[\Since('8.1')]
function ldap_set_option(?\LDAP\Connection $ldap, int $option, $value) : bool
{
}