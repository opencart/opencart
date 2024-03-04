<?php 

#endif
/**
 * @param resource|array $ldap
 * @return resource|array|false
 */
#[\Until('8.1')]
function ldap_read($ldap, array|string $base, array|string $filter, array $attributes = [], int $attributes_only = 0, int $sizelimit = -1, int $timelimit = -1, int $deref = LDAP_DEREF_NEVER, ?array $controls = null)
{
}
#endif
/** @param LDAP\Connection|array $ldap */
#[\Since('8.1')]
function ldap_read($ldap, array|string $base, array|string $filter, array $attributes = [], int $attributes_only = 0, int $sizelimit = -1, int $timelimit = -1, int $deref = LDAP_DEREF_NEVER, ?array $controls = null) : \LDAP\Result|array|false
{
}