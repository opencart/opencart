<?php
/**
 * Stubs for uploadprogress extension
 * @link https://pecl.php.net/package/uploadprogress
 */

/**
 * @param string $identifier
 * @param string $fieldname
 * @param int $maxlen
 * @return string|null
 * @since PECL uploadprogress >= 0.9.0
 */
function uploadprogress_get_contents(string $identifier, string $fieldname, int $maxlen = -1): ?string {}

/**
 * @param string $identifier
 * @return array|null
 * @since PECL uploadprogress >= 0.3.0
 */
function uploadprogress_get_info(string $identifier): ?array {}
