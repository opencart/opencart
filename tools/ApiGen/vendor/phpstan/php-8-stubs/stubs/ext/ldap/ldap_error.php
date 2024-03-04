<?php 

/** @param resource $ldap */
#[\Until('8.1')]
function ldap_error($ldap) : string
{
}
#[\Since('8.1')]
function ldap_error(\LDAP\Connection $ldap) : string
{
}