<?php 

#endif
#endif
#if defined(LDAP_API_FEATURE_X_OPENLDAP) && defined(HAVE_3ARG_SETREBINDPROC)
/** @param resource $ldap */
#[\Until('8.1')]
function ldap_set_rebind_proc($ldap, ?callable $callback) : bool
{
}
#endif
#endif
#if defined(LDAP_API_FEATURE_X_OPENLDAP) && defined(HAVE_3ARG_SETREBINDPROC)
#[\Since('8.1')]
function ldap_set_rebind_proc(\LDAP\Connection $ldap, ?callable $callback) : bool
{
}