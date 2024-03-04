<?php

// Start of uuid v.1.1.0

define('UUID_VARIANT_NCS', 0);
define('UUID_VARIANT_DCE', 1);
define('UUID_VARIANT_MICROSOFT', 2);
define('UUID_VARIANT_OTHER', 3);

define('UUID_TYPE_DEFAULT', 0);
define('UUID_TYPE_DCE', 4);
define('UUID_TYPE_NAME', 1);
define('UUID_TYPE_TIME', 1);
define('UUID_TYPE_SECURITY', 2);
define('UUID_TYPE_MD5', 3);
define('UUID_TYPE_RANDOM', 4);
define('UUID_TYPE_SHA1', 5);
define('UUID_TYPE_NULL', -1);
define('UUID_TYPE_INVALID', -42);

/**
 * Generate a new UUID
 *
 * @param int $uuid_type [optional] Type of UUID to generat
 * @return string The string representation of a UUID
 */
function uuid_create($uuid_type = UUID_TYPE_DEFAULT) {}

/**
 * Check whether a given UUID string is a valid UUID
 *
 * @param string $uuid The UUID string to validate
 * @return bool Whether or not the UUID was valid
 */
function uuid_is_valid($uuid) {}

/**
 * Compare two UUIDs
 *
 * Returns an integer less than, equal to, or greater than zero if $uuid1 is
 * found, respectively, to be lexicographically less than, equal, or greater
 * than $uuid2
 *
 * @param string $uuid1 First UUID
 * @param string $uuid2 Second UUID
 * @return int 0 if equal, otherwise a positive or negative integer
 */
function uuid_compare($uuid1, $uuid2) {}

/**
 * Check wheter an UUID is the NULL UUID 00000000-0000-0000-0000-000000000000
 *
 * @param string $uuid
 * @return bool Returns true if the UUID is null
 */
function uuid_is_null($uuid) {}

/**
 * Generate a MD5 hashed (predictable) UUID based on a well-known UUID
 *
 * This function is only availble if the PECL extension was compiled with a
 * version of libuuid that contains uuid_generate_md5.
 *
 * @param string $uuid_ns
 * @param string $name
 * @return string The new UUID
 */
function uuid_generate_md5($uuid_ns, $name) {}

/**
 * Generate a SHA1 hashed (predictable) UUID based on a well-known UUID
 *
 * This function is only available if the PECL extension was compiled with a
 * version of libuuid that contains uuid_generate_sha1.
 *
 * @param string $uuid_ns
 * @param string $name
 * @return string The new UUID
 */
function uuid_generate_sha1($uuid_ns, $name) {}

/**
 * Return the UUIDs type
 *
 * @param string $uuid
 * @return int Any one of the UUID_TYPE_* constants
 */
function uuid_type($uuid) {}

/**
 * Return the UUIDs variant
 *
 * @param string $uuid
 * @return int Any one of the UUID_VARIANT_* constants
 */
function uuid_variant($uuid) {}

/**
 * Extract creation time from a time based UUID as UNIX timestamp
 *
 * @param string $uuid
 * @return false|int Returns a unix timestamp on success or false on failure
 */
function uuid_time($uuid) {}

/**
 * Get UUID creator network MAC address
 *
 * @param string $uuid
 * @return false|string Returns the MAC address on succes or false on failure
 */
function uuid_mac($uuid) {}

/**
 * Converts a UUID string to the binary representation
 *
 * @param string $uuid
 * @return false|string Binary string of the UUID on success or false on failure
 */
function uuid_parse($uuid) {}

/**
 * Converts a UUID binary string to the human readable string
 *
 * @param string $uuid
 * @return false|string String UUID on success or false on failure
 */
function uuid_unparse($uuid) {}
