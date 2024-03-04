<?php 

#endif
#ifdef HAVE_LDAP_WHOAMI_S
/** @param resource $ldap */
#[\Until('8.1')]
function ldap_exop_whoami($ldap) : string|false
{
}
#endif
#ifdef HAVE_LDAP_WHOAMI_S
#[\Since('8.1')]
function ldap_exop_whoami(\LDAP\Connection $ldap) : string|false
{
}