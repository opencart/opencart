<?php 

/**
 * @param resource $ldap
 * @param resource $result
 */
#[\Until('8.1')]
function ldap_count_references($ldap, $result) : int
{
}
#[\Since('8.1')]
function ldap_count_references(\LDAP\Connection $ldap, \LDAP\Result $result) : int
{
}