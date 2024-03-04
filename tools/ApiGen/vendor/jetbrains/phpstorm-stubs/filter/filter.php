<?php

// Start of filter v.0.11.0
use JetBrains\PhpStorm\Pure;

/**
 * Gets a specific external variable by name and optionally filters it
 * @link https://php.net/manual/en/function.filter-input.php
 * @param int $type <p>
 * One of <b>INPUT_GET</b>, <b>INPUT_POST</b>,
 * <b>INPUT_COOKIE</b>, <b>INPUT_SERVER</b>, or
 * <b>INPUT_ENV</b>.
 * </p>
 * @param string $var_name <p>
 * Name of a variable to get.
 * </p>
 * @param int $filter [optional] <p>
 * The ID of the filter to apply. The
 * manual page lists the available filters.
 * </p>
 * @param array|int $options <p>
 * Associative array of options or bitwise disjunction of flags. If filter
 * accepts options, flags can be provided in "flags" field of array.
 * </p>
 * @return mixed Value of the requested variable on success, <b>FALSE</b> if the filter fails,
 * or <b>NULL</b> if the <i>variable_name</i> variable is not set.
 * If the flag <b>FILTER_NULL_ON_FAILURE</b> is used, it
 * returns <b>FALSE</b> if the variable is not set and <b>NULL</b> if the filter fails.
 */
#[Pure]
function filter_input(int $type, string $var_name, int $filter = FILTER_DEFAULT, array|int $options = 0): mixed {}

/**
 * Filters a variable with a specified filter
 * @link https://php.net/manual/en/function.filter-var.php
 * @param mixed $value <p>
 * Value to filter.
 * </p>
 * @param int $filter [optional] <p>
 * The ID of the filter to apply. The
 * manual page lists the available filters.
 * </p>
 * @param array|int $options <p>
 * Associative array of options or bitwise disjunction of flags. If filter
 * accepts options, flags can be provided in "flags" field of array. For
 * the "callback" filter, callable type should be passed. The
 * callback must accept one argument, the value to be filtered, and return
 * the value after filtering/sanitizing it.
 * </p>
 * <p>
 * <code>
 * // for filters that accept options, use this format
 * $options = array(
 * 'options' => array(
 * 'default' => 3, // value to return if the filter fails
 * // other options here
 * 'min_range' => 0
 * ),
 * 'flags' => FILTER_FLAG_ALLOW_OCTAL,
 * );
 * $var = filter_var('0755', FILTER_VALIDATE_INT, $options);
 * // for filter that only accept flags, you can pass them directly
 * $var = filter_var('oops', FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
 * // for filter that only accept flags, you can also pass as an array
 * $var = filter_var('oops', FILTER_VALIDATE_BOOLEAN,
 * array('flags' => FILTER_NULL_ON_FAILURE));
 * // callback validate filter
 * function foo($value)
 * {
 * // Expected format: Surname, GivenNames
 * if (strpos($value, ", ") === false) return false;
 * list($surname, $givennames) = explode(", ", $value, 2);
 * $empty = (empty($surname) || empty($givennames));
 * $notstrings = (!is_string($surname) || !is_string($givennames));
 * if ($empty || $notstrings) {
 * return false;
 * } else {
 * return $value;
 * }
 * }
 * $var = filter_var('Doe, Jane Sue', FILTER_CALLBACK, array('options' => 'foo'));
 * </code>
 * </p>
 * @return mixed the filtered data, or <b>FALSE</b> if the filter fails.
 */
#[Pure]
function filter_var(mixed $value, int $filter = FILTER_DEFAULT, array|int $options = 0): mixed {}

/**
 * Gets external variables and optionally filters them
 * @link https://php.net/manual/en/function.filter-input-array.php
 * @param int $type <p>
 * One of <b>INPUT_GET</b>, <b>INPUT_POST</b>,
 * <b>INPUT_COOKIE</b>, <b>INPUT_SERVER</b>, or
 * <b>INPUT_ENV</b>.
 * </p>
 * @param array|int $options [optional] <p>
 * An array defining the arguments. A valid key is a string
 * containing a variable name and a valid value is either a filter type, or an array
 * optionally specifying the filter, flags and options. If the value is an
 * array, valid keys are filter which specifies the
 * filter type,
 * flags which specifies any flags that apply to the
 * filter, and options which specifies any options that
 * apply to the filter. See the example below for a better understanding.
 * </p>
 * <p>
 * This parameter can be also an integer holding a filter constant. Then all values in the
 * input array are filtered by this filter.
 * </p>
 * @param bool $add_empty [optional] <p>
 * Add missing keys as <b>NULL</b> to the return value.
 * </p>
 * @return array|false|null An array containing the values of the requested variables on success, or <b>FALSE</b>
 * on failure. An array value will be <b>FALSE</b> if the filter fails, or <b>NULL</b> if
 * the variable is not set. Or if the flag <b>FILTER_NULL_ON_FAILURE</b>
 * is used, it returns <b>FALSE</b> if the variable is not set and <b>NULL</b> if the filter
 * fails.
 */
#[Pure]
function filter_input_array(int $type, array|int $options = FILTER_DEFAULT, bool $add_empty = true): array|false|null {}

/**
 * Gets multiple variables and optionally filters them
 * @link https://php.net/manual/en/function.filter-var-array.php
 * @param array $array <p>
 * An array with string keys containing the data to filter.
 * </p>
 * @param array|int $options [optional] <p>
 * An array defining the arguments. A valid key is a string
 * containing a variable name and a valid value is either a
 * filter type, or an
 * array optionally specifying the filter, flags and options.
 * If the value is an array, valid keys are filter
 * which specifies the filter type,
 * flags which specifies any flags that apply to the
 * filter, and options which specifies any options that
 * apply to the filter. See the example below for a better understanding.
 * </p>
 * <p>
 * This parameter can be also an integer holding a filter constant. Then all values in the
 * input array are filtered by this filter.
 * </p>
 * @param bool $add_empty [optional] <p>
 * Add missing keys as <b>NULL</b> to the return value.
 * </p>
 * @return array|false|null An array containing the values of the requested variables on success, or <b>FALSE</b>
 * on failure. An array value will be <b>FALSE</b> if the filter fails, or <b>NULL</b> if
 * the variable is not set.
 */
#[Pure]
function filter_var_array(array $array, array|int $options = FILTER_DEFAULT, bool $add_empty = true): array|false|null {}

/**
 * Returns a list of all supported filters
 * @link https://php.net/manual/en/function.filter-list.php
 * @return array an array of names of all supported filters, empty array if there
 * are no such filters. Indexes of this array are not filter IDs, they can be
 * obtained with <b>filter_id</b> from a name instead.
 */
#[Pure]
function filter_list(): array {}

/**
 * Checks if variable of specified type exists
 * @link https://php.net/manual/en/function.filter-has-var.php
 * @param int $input_type <p>
 * One of <b>INPUT_GET</b>, <b>INPUT_POST</b>,
 * <b>INPUT_COOKIE</b>, <b>INPUT_SERVER</b>, or
 * <b>INPUT_ENV</b>.
 * </p>
 * @param string $var_name <p>
 * Name of a variable to check.
 * </p>
 * @return bool <b>TRUE</b> on success or <b>FALSE</b> on failure.
 */
#[Pure]
function filter_has_var(int $input_type, string $var_name): bool {}

/**
 * Returns the filter ID belonging to a named filter
 * @link https://php.net/manual/en/function.filter-id.php
 * @param string $name <p>
 * Name of a filter to get.
 * </p>
 * @return int|false ID of a filter on success or <b>FALSE</b> if filter doesn't exist.
 */
#[Pure]
function filter_id(string $name): int|false {}

/**
 * POST variables.
 * @link https://php.net/manual/en/filter.constants.php
 */
define('INPUT_POST', 0);

/**
 * GET variables.
 * @link https://php.net/manual/en/filter.constants.php
 */
define('INPUT_GET', 1);

/**
 * COOKIE variables.
 * @link https://php.net/manual/en/filter.constants.php
 */
define('INPUT_COOKIE', 2);

/**
 * ENV variables.
 * @link https://php.net/manual/en/filter.constants.php
 */
define('INPUT_ENV', 4);

/**
 * SERVER variables.
 * @link https://php.net/manual/en/filter.constants.php
 */
define('INPUT_SERVER', 5);

/**
 * SESSION variables.
 * (not implemented yet)
 * @link https://php.net/manual/en/filter.constants.php
 * @removed 8.0
 */
define('INPUT_SESSION', 6);

/**
 * REQUEST variables.
 * (not implemented yet)
 * @link https://php.net/manual/en/filter.constants.php
 * @removed 8.0
 */
define('INPUT_REQUEST', 99);

/**
 * No flags.
 * @link https://php.net/manual/en/filter.constants.php
 */
define('FILTER_FLAG_NONE', 0);

/**
 * Flag used to require scalar as input
 * @link https://php.net/manual/en/filter.constants.php
 */
define('FILTER_REQUIRE_SCALAR', 33554432);

/**
 * Require an array as input.
 * @link https://php.net/manual/en/filter.constants.php
 */
define('FILTER_REQUIRE_ARRAY', 16777216);

/**
 * Always returns an array.
 * @link https://php.net/manual/en/filter.constants.php
 */
define('FILTER_FORCE_ARRAY', 67108864);

/**
 * Use NULL instead of FALSE on failure.
 * @link https://php.net/manual/en/filter.constants.php
 */
define('FILTER_NULL_ON_FAILURE', 134217728);

/**
 * ID of "int" filter.
 * @link https://php.net/manual/en/filter.constants.php
 */
define('FILTER_VALIDATE_INT', 257);

/**
 * ID of "boolean" filter.
 * @link https://php.net/manual/en/filter.constants.php
 */
define('FILTER_VALIDATE_BOOLEAN', 258);
/**
 * ID of "boolean" filter.
 * @link https://php.net/manual/en/filter.constants.php
 * @link https://php.net/manual/en/filter.filters.validate.php
 * @since 8.0 Using `FILTER_VALIDATE_BOOL` is preferred.
 */
define('FILTER_VALIDATE_BOOL', 258);

/**
 * ID of "float" filter.
 * @link https://php.net/manual/en/filter.constants.php
 */
define('FILTER_VALIDATE_FLOAT', 259);

/**
 * ID of "validate_regexp" filter.
 * @link https://php.net/manual/en/filter.constants.php
 */
define('FILTER_VALIDATE_REGEXP', 272);

define('FILTER_VALIDATE_DOMAIN', 277);

/**
 * ID of "validate_url" filter.
 * @link https://php.net/manual/en/filter.constants.php
 */
define('FILTER_VALIDATE_URL', 273);

/**
 * ID of "validate_email" filter.
 * @link https://php.net/manual/en/filter.constants.php
 */
define('FILTER_VALIDATE_EMAIL', 274);

/**
 * ID of "validate_ip" filter.
 * @link https://php.net/manual/en/filter.constants.php
 */
define('FILTER_VALIDATE_IP', 275);
define('FILTER_VALIDATE_MAC', 276);

/**
 * ID of default ("string") filter.
 * @link https://php.net/manual/en/filter.constants.php
 */
define('FILTER_DEFAULT', 516);

/**
 * @since 7.3
 */
define('FILTER_SANITIZE_ADD_SLASHES', 523);

/**
 * ID of "unsafe_raw" filter.
 * @link https://php.net/manual/en/filter.constants.php
 */
define('FILTER_UNSAFE_RAW', 516);

/**
 * ID of "string" filter.
 * @link https://php.net/manual/en/filter.constants.php
 * @deprecated 8.1
 */
define('FILTER_SANITIZE_STRING', 513);

/**
 * ID of "stripped" filter.
 * @link https://php.net/manual/en/filter.constants.php
 * @deprecated 8.1
 */
define('FILTER_SANITIZE_STRIPPED', 513);

/**
 * ID of "encoded" filter.
 * @link https://php.net/manual/en/filter.constants.php
 */
define('FILTER_SANITIZE_ENCODED', 514);

/**
 * ID of "special_chars" filter.
 * @link https://php.net/manual/en/filter.constants.php
 */
define('FILTER_SANITIZE_SPECIAL_CHARS', 515);
define('FILTER_SANITIZE_FULL_SPECIAL_CHARS', 522);

/**
 * ID of "email" filter.
 * @link https://php.net/manual/en/filter.constants.php
 */
define('FILTER_SANITIZE_EMAIL', 517);

/**
 * ID of "url" filter.
 * @link https://php.net/manual/en/filter.constants.php
 */
define('FILTER_SANITIZE_URL', 518);

/**
 * ID of "number_int" filter.
 * @link https://php.net/manual/en/filter.constants.php
 */
define('FILTER_SANITIZE_NUMBER_INT', 519);

/**
 * ID of "number_float" filter.
 * @link https://php.net/manual/en/filter.constants.php
 */
define('FILTER_SANITIZE_NUMBER_FLOAT', 520);

/**
 * ID of "magic_quotes" filter.
 * @link https://php.net/manual/en/filter.constants.php
 * @deprecated 7.4
 * @removed 8.0
 */
define('FILTER_SANITIZE_MAGIC_QUOTES', 521);

/**
 * ID of "callback" filter.
 * @link https://php.net/manual/en/filter.constants.php
 */
define('FILTER_CALLBACK', 1024);

/**
 * Allow octal notation (0[0-7]+) in "int" filter.
 * @link https://php.net/manual/en/filter.constants.php
 */
define('FILTER_FLAG_ALLOW_OCTAL', 1);

/**
 * Allow hex notation (0x[0-9a-fA-F]+) in "int" filter.
 * @link https://php.net/manual/en/filter.constants.php
 */
define('FILTER_FLAG_ALLOW_HEX', 2);

/**
 * Strip characters with ASCII value less than 32.
 * @link https://php.net/manual/en/filter.constants.php
 */
define('FILTER_FLAG_STRIP_LOW', 4);

/**
 * Strip characters with ASCII value greater than 127.
 * @link https://php.net/manual/en/filter.constants.php
 */
define('FILTER_FLAG_STRIP_HIGH', 8);
define('FILTER_FLAG_STRIP_BACKTICK', 512);

/**
 * Encode characters with ASCII value less than 32.
 * @link https://php.net/manual/en/filter.constants.php
 */
define('FILTER_FLAG_ENCODE_LOW', 16);

/**
 * Encode characters with ASCII value greater than 127.
 * @link https://php.net/manual/en/filter.constants.php
 */
define('FILTER_FLAG_ENCODE_HIGH', 32);

/**
 * Encode &.
 * @link https://php.net/manual/en/filter.constants.php
 */
define('FILTER_FLAG_ENCODE_AMP', 64);

/**
 * Don't encode ' and ".
 * @link https://php.net/manual/en/filter.constants.php
 */
define('FILTER_FLAG_NO_ENCODE_QUOTES', 128);

/**
 * (No use for now.)
 * @link https://php.net/manual/en/filter.constants.php
 */
define('FILTER_FLAG_EMPTY_STRING_NULL', 256);

/**
 * Allow fractional part in "number_float" filter.
 * @link https://php.net/manual/en/filter.constants.php
 */
define('FILTER_FLAG_ALLOW_FRACTION', 4096);

/**
 * Allow thousand separator (,) in "number_float" filter.
 * @link https://php.net/manual/en/filter.constants.php
 */
define('FILTER_FLAG_ALLOW_THOUSAND', 8192);

/**
 * Allow scientific notation (e, E) in
 * "number_float" filter.
 * @link https://php.net/manual/en/filter.constants.php
 */
define('FILTER_FLAG_ALLOW_SCIENTIFIC', 16384);

/**
 * Require scheme in "validate_url" filter.
 * @link https://php.net/manual/en/filter.constants.php
 * @deprecated 7.3
 * @removed 8.0
 */
define('FILTER_FLAG_SCHEME_REQUIRED', 65536);

/**
 * Require host in "validate_url" filter.
 * @link https://php.net/manual/en/filter.constants.php
 * @deprecated 7.3
 * @removed 8.0
 */
define('FILTER_FLAG_HOST_REQUIRED', 131072);

/**
 * Require path in "validate_url" filter.
 * @link https://php.net/manual/en/filter.constants.php
 */
define('FILTER_FLAG_PATH_REQUIRED', 262144);

/**
 * Require query in "validate_url" filter.
 * @link https://php.net/manual/en/filter.constants.php
 */
define('FILTER_FLAG_QUERY_REQUIRED', 524288);

/**
 * Allow only IPv4 address in "validate_ip" filter.
 * @link https://php.net/manual/en/filter.constants.php
 */
define('FILTER_FLAG_IPV4', 1048576);

/**
 * Allow only IPv6 address in "validate_ip" filter.
 * @link https://php.net/manual/en/filter.constants.php
 */
define('FILTER_FLAG_IPV6', 2097152);

/**
 * Deny reserved addresses in "validate_ip" filter.
 * @link https://php.net/manual/en/filter.constants.php
 */
define('FILTER_FLAG_NO_RES_RANGE', 4194304);

/**
 * Deny private addresses in "validate_ip" filter.
 * @link https://php.net/manual/en/filter.constants.php
 */
define('FILTER_FLAG_NO_PRIV_RANGE', 8388608);

define('FILTER_FLAG_HOSTNAME', 1048576);
define('FILTER_FLAG_EMAIL_UNICODE', 1048576);

/**
 * filters Global IPs per RFC 6890
 * @since 8.2
 */
define('FILTER_FLAG_GLOBAL_RANGE', 268435456);

// End of filter v.0.11.0
