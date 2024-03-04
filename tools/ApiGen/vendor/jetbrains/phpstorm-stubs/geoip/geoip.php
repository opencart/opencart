<?php

// Start of geoip v.1.1.0
use JetBrains\PhpStorm\Pure;

/**
 * (PECL geoip &gt;= 0.2.0)<br/>
 * Get GeoIP Database information
 * @link https://php.net/manual/en/function.geoip-database-info.php
 * @param int $database [optional] <p>
 * The database type as an integer. You can use the
 * various constants defined with
 * this extension (ie: GEOIP_*_EDITION).
 * </p>
 * @return string|null the corresponding database version, or <b>NULL</b> on error.
 */
#[Pure]
function geoip_database_info($database = GEOIP_COUNTRY_EDITION) {}

/**
 * (PECL geoip &gt;= 0.2.0)<br/>
 * Get the two letter country code
 * @link https://php.net/manual/en/function.geoip-country-code-by-name.php
 * @param string $hostname <p>
 * The hostname or IP address whose location is to be looked-up.
 * </p>
 * @return string|false the two letter ISO country code on success, or <b>FALSE</b>
 * if the address cannot be found in the database.
 */
#[Pure]
function geoip_country_code_by_name($hostname) {}

/**
 * (PECL geoip &gt;= 0.2.0)<br/>
 * Get the three letter country code
 * @link https://php.net/manual/en/function.geoip-country-code3-by-name.php
 * @param string $hostname <p>
 * The hostname or IP address whose location is to be looked-up.
 * </p>
 * @return string|false the three letter country code on success, or <b>FALSE</b>
 * if the address cannot be found in the database.
 */
#[Pure]
function geoip_country_code3_by_name($hostname) {}

/**
 * (PECL geoip &gt;= 0.2.0)<br/>
 * Get the full country name
 * @link https://php.net/manual/en/function.geoip-country-name-by-name.php
 * @param string $hostname <p>
 * The hostname or IP address whose location is to be looked-up.
 * </p>
 * @return string|false the country name on success, or <b>FALSE</b> if the address cannot
 * be found in the database.
 */
#[Pure]
function geoip_country_name_by_name($hostname) {}

/**
 * (PECL geoip &gt;= 1.0.3)<br/>
 * Get the two letter continent code
 * @link https://php.net/manual/en/function.geoip-continent-code-by-name.php
 * @param string $hostname <p>
 * The hostname or IP address whose location is to be looked-up.
 * </p>
 * @return string|false the two letter continent code on success, or <b>FALSE</b> if the
 * address cannot be found in the database.
 */
#[Pure]
function geoip_continent_code_by_name($hostname) {}

/**
 * (PECL geoip &gt;= 0.2.0)<br/>
 * Get the organization name
 * @link https://php.net/manual/en/function.geoip-org-by-name.php
 * @param string $hostname <p>
 * The hostname or IP address.
 * </p>
 * @return string|false the organization name on success, or <b>FALSE</b> if the address
 * cannot be found in the database.
 */
#[Pure]
function geoip_org_by_name($hostname) {}

/**
 * (PECL geoip &gt;= 0.2.0)<br/>
 * Returns the detailed City information found in the GeoIP Database
 * @link https://php.net/manual/en/function.geoip-record-by-name.php
 * @param string $hostname <p>
 * The hostname or IP address whose record is to be looked-up.
 * </p>
 * @return array|false the associative array on success, or <b>FALSE</b> if the address
 * cannot be found in the database.
 */
#[Pure]
function geoip_record_by_name($hostname) {}

/**
 * (PECL geoip &gt;= 0.2.0)<br/>
 * Get the Internet connection type
 * @link https://php.net/manual/en/function.geoip-id-by-name.php
 * @param string $hostname <p>
 * The hostname or IP address whose connection type is to be looked-up.
 * </p>
 * @return int the connection type.
 */
#[Pure]
function geoip_id_by_name($hostname) {}

/**
 * (PECL geoip &gt;= 0.2.0)<br/>
 * Get the country code and region
 * @link https://php.net/manual/en/function.geoip-region-by-name.php
 * @param string $hostname <p>
 * The hostname or IP address whose region is to be looked-up.
 * </p>
 * @return array|false the associative array on success, or <b>FALSE</b> if the address
 * cannot be found in the database.
 */
#[Pure]
function geoip_region_by_name($hostname) {}

/**
 * (PECL geoip &gt;= 1.0.2)<br/>
 * Get the Internet Service Provider (ISP) name
 * @link https://php.net/manual/en/function.geoip-isp-by-name.php
 * @param string $hostname <p>
 * The hostname or IP address.
 * </p>
 * @return string|false the ISP name on success, or <b>FALSE</b> if the address
 * cannot be found in the database.
 */
#[Pure]
function geoip_isp_by_name($hostname) {}

/**
 * (PECL geoip &gt;= 1.0.1)<br/>
 * Determine if GeoIP Database is available
 * @link https://php.net/manual/en/function.geoip-db-avail.php
 * @param int $database <p>
 * The database type as an integer. You can use the
 * various constants defined with
 * this extension (ie: GEOIP_*_EDITION).
 * </p>
 * @return bool|null <b>TRUE</b> is database exists, <b>FALSE</b> if not found, or <b>NULL</b> on error.
 */
#[Pure]
function geoip_db_avail($database) {}

/**
 * (PECL geoip &gt;= 1.0.1)<br/>
 * Returns detailed information about all GeoIP database types
 * @link https://php.net/manual/en/function.geoip-db-get-all-info.php
 * @return array the associative array.
 */
#[Pure]
function geoip_db_get_all_info() {}

/**
 * (PECL geoip &gt;= 1.0.1)<br/>
 * Returns the filename of the corresponding GeoIP Database
 * @link https://php.net/manual/en/function.geoip-db-filename.php
 * @param int $database <p>
 * The database type as an integer. You can use the
 * various constants defined with
 * this extension (ie: GEOIP_*_EDITION).
 * </p>
 * @return string|null the filename of the corresponding database, or <b>NULL</b> on error.
 */
#[Pure]
function geoip_db_filename($database) {}

/**
 * (PECL geoip &gt;= 1.0.4)<br/>
 * Returns the region name for some country and region code combo
 * @link https://php.net/manual/en/function.geoip-region-name-by-code.php
 * @param string $country_code <p>
 * The two-letter country code (see
 * <b>geoip_country_code_by_name</b>)
 * </p>
 * @param string $region_code <p>
 * The two-letter (or digit) region code (see
 * <b>geoip_region_by_name</b>)
 * </p>
 * @return string|false the region name on success, or <b>FALSE</b> if the country and region code
 * combo cannot be found.
 */
#[Pure]
function geoip_region_name_by_code($country_code, $region_code) {}

/**
 * (PECL geoip &gt;= 1.0.4)<br/>
 * Returns the time zone for some country and region code combo
 * @link https://php.net/manual/en/function.geoip-time-zone-by-country-and-region.php
 * @param string $country_code <p>
 * The two-letter country code (see
 * <b>geoip_country_code_by_name</b>)
 * </p>
 * @param string $region_code [optional] <p>
 * The two-letter (or digit) region code (see
 * <b>geoip_region_by_name</b>)
 * </p>
 * @return string|false the time zone on success, or <b>FALSE</b> if the country and region code
 * combo cannot be found.
 */
#[Pure]
function geoip_time_zone_by_country_and_region($country_code, $region_code = null) {}

define('GEOIP_COUNTRY_EDITION', 1);
define('GEOIP_REGION_EDITION_REV0', 7);
define('GEOIP_CITY_EDITION_REV0', 6);
define('GEOIP_ORG_EDITION', 5);
define('GEOIP_ISP_EDITION', 4);
define('GEOIP_CITY_EDITION_REV1', 2);
define('GEOIP_REGION_EDITION_REV1', 3);
define('GEOIP_PROXY_EDITION', 8);
define('GEOIP_ASNUM_EDITION', 9);
define('GEOIP_NETSPEED_EDITION', 10);
define('GEOIP_DOMAIN_EDITION', 11);
define('GEOIP_UNKNOWN_SPEED', 0);
define('GEOIP_DIALUP_SPEED', 1);
define('GEOIP_CABLEDSL_SPEED', 2);
define('GEOIP_CORPORATE_SPEED', 3);

/**
 * (PECL geoip &gt;= 1.1.0)<br/>
 * <p>
 * The geoip_asnum_by_name() function will return the Autonomous System Numbers (ASN) associated with an IP address.
 * </p>
 * @link https://secure.php.net/manual/en/function.geoip-asnum-by-name.php
 * @param string $hostname The hostname or IP address
 *
 * @return string|false Returns the ASN on success, or <b>FALSE</b> if the address cannot be found in the database.
 * @since 1.1.0
 */
function geoip_asnum_by_name($hostname) {}

/**
 * (PECL geoip &gt;= 1.1.0)<br/>
 * <p>
 * The geoip_netspeedcell_by_name() function will return the Internet connection type and speed corresponding to a hostname or an IP address.<br>
 * <br>
 * This function is only available if using GeoIP Library version 1.4.8 or newer.<br>
 * <br>
 * This function is currently only available to users who have bought a commercial GeoIP NetSpeedCell Edition. A warning will be issued if the proper database cannot be located.<br>
 * <br>
 * The return value is a string, common values are:<br>
 * - Cable/DSL<br>
 * - Dialup<br>
 * - Cellular<br>
 * - Corporate<br>
 * </p>
 * @link https://secure.php.net/manual/en/function.geoip-netspeedcell-by-name.php
 * @param string $hostname The hostname or IP address
 *
 * @return string|false Returns the connection speed on success, or <b>FALSE</b> if the address cannot be found in the database.
 * @since 1.1.0
 */
function geoip_netspeedcell_by_name($hostname) {}

/**
 * (PECL geoip &gt;= 1.1.0)<br/>
 * <p>
 * The geoip_setup_custom_directory() function will change the default directory of the GeoIP database. This is equivalent to changing geoip.custom_directory
 * </p>
 * @link https://secure.php.net/manual/en/function.geoip-setup-custom-directory.php
 * @param string $path The full path of where the GeoIP database is on disk.
 *
 * @return void
 * @since 1.1.0
 */
function geoip_setup_custom_directory($path) {}

// End of geoip v.1.1.0
