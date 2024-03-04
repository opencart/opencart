<?php 

/**
 * @param array $authoritative_name_servers
 * @param array $additional_records
 * @refcount 1
 */
function dns_get_record(string $hostname, int $type = DNS_ANY, &$authoritative_name_servers = null, &$additional_records = null, bool $raw = false) : array|false
{
}