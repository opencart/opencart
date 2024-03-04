<?php 

#endif
/** @param resource $ldap */
#[\Until('8.1')]
function ldap_unbind($ldap) : bool
{
}
#endif
#[\Since('8.1')]
function ldap_unbind(\LDAP\Connection $ldap) : bool
{
}