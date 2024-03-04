<?php 

#endif
#ifdef HAVE_LDAP_START_TLS_S
/** @param resource $ldap */
#[\Until('8.1')]
function ldap_start_tls($ldap) : bool
{
}
#endif
#ifdef HAVE_LDAP_START_TLS_S
#[\Since('8.1')]
function ldap_start_tls(\LDAP\Connection $ldap) : bool
{
}