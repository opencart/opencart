<?php 

/**
 * @param resource $ldap
 * @param resource $entry
 * @return resource|false
 */
#[\Until('8.1')]
function ldap_next_entry($ldap, $entry)
{
}
#[\Since('8.1')]
function ldap_next_entry(\LDAP\Connection $ldap, \LDAP\ResultEntry $entry) : \LDAP\ResultEntry|false
{
}