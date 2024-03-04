<?php 

/** @param resource $ldap */
#[\Until('8.1')]
function ldap_errno($ldap) : int
{
}
#[\Since('8.1')]
function ldap_errno(\LDAP\Connection $ldap) : int
{
}