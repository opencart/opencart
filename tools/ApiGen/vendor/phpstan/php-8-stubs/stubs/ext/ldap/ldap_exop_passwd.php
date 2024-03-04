<?php 

#endif
#ifdef HAVE_LDAP_PASSWD
/**
 * @param resource $ldap
 * @param array $controls
 */
#[\Until('8.1')]
function ldap_exop_passwd($ldap, string $user = "", string $old_password = "", string $new_password = "", &$controls = null) : string|bool
{
}
#endif
#ifdef HAVE_LDAP_PASSWD
/**
 * @param array $controls
 */
#[\Since('8.1')]
function ldap_exop_passwd(\LDAP\Connection $ldap, string $user = "", string $old_password = "", string $new_password = "", &$controls = null) : string|bool
{
}