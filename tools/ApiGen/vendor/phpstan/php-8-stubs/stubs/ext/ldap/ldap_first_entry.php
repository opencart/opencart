<?php 

/**
 * @param resource $ldap
 * @param resource $result
 * @return resource|false
 */
#[\Until('8.1')]
function ldap_first_entry($ldap, $result)
{
}
#[\Since('8.1')]
function ldap_first_entry(\LDAP\Connection $ldap, \LDAP\Result $result) : \LDAP\ResultEntry|false
{
}