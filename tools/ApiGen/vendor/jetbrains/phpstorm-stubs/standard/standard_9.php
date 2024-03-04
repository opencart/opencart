<?php

/**
 * @since 5.6
 */

use JetBrains\PhpStorm\ExpectedValues;
use JetBrains\PhpStorm\Internal\LanguageLevelTypeAware;
use JetBrains\PhpStorm\Internal\PhpStormStubsElementAvailable;
use JetBrains\PhpStorm\Pure;

define("ARRAY_FILTER_USE_BOTH", 1);

/**
 * @since 5.6
 */
define("ARRAY_FILTER_USE_KEY", 2);

/**
 * Merge two or more arrays recursively
 * @link https://php.net/manual/en/function.array-merge-recursive.php
 * @param array ...$arrays Variable list of arrays to recursively merge.
 * @return array An array of values resulted from merging the arguments together.
 */
#[Pure]
function array_merge_recursive(
    #[PhpStormStubsElementAvailable(from: '5.3', to: '7.3')] array $arr1,
    #[PhpStormStubsElementAvailable(from: '5.3', to: '7.0')] array $arrays,
    array ...$arrays
): array {}

/**
 * array_replace() replaces the values of the first array with the same values from all the following arrays.
 * If a key from the first array exists in the second array, its value will be replaced by the value from the second array.
 * If the key exists in the second array, and not the first, it will be created in the first array.
 * If a key only exists in the first array, it will be left as is. If several arrays are passed for replacement,
 * they will be processed in order, the later arrays overwriting the previous values.
 * array_replace() is not recursive : it will replace values in the first array by whatever type is in the second array.
 * @link https://php.net/manual/en/function.array-replace.php
 * @param array $array <p>
 * The array in which elements are replaced.
 * </p>
 * @param array ...$replacements <p>
 * The array from which elements will be extracted.
 * </p>
 * @return array or null if an error occurs.
 */
#[Pure]
function array_replace(
    array $array,
    #[PhpStormStubsElementAvailable(from: '5.3', to: '7.0')] $replacements,
    array ...$replacements
): array {}

/**
 * Replaces elements from passed arrays into the first array recursively
 * @link https://php.net/manual/en/function.array-replace-recursive.php
 * @param array $array <p>
 * The array in which elements are replaced.
 * </p>
 * @param array ...$replacements <p>
 * The array from which elements will be extracted.
 * </p>
 * @return array an array, or null if an error occurs.
 */
#[Pure]
function array_replace_recursive(
    array $array,
    #[PhpStormStubsElementAvailable(from: '5.3', to: '7.0')] $replacements,
    array ...$replacements
): array {}

/**
 * Return all the keys or a subset of the keys of an array
 * @link https://php.net/manual/en/function.array-keys.php
 * @param array $array <p>
 * An array containing keys to return.
 * </p>
 * @param mixed $filter_value [optional] <p>
 * If specified, then only keys containing these values are returned.
 * </p>
 * @param bool $strict [optional] <p>
 * Determines if strict comparison (===) should be used during the search.
 * </p>
 * @return int[]|string[] an array of all the keys in input.
 */
#[Pure]
function array_keys(array $array, mixed $filter_value, bool $strict = false): array {}

/**
 * Return all the values of an array
 * @link https://php.net/manual/en/function.array-values.php
 * @param array $array <p>
 * The array.
 * </p>
 * @return array an indexed array of values.
 * @meta
 */
#[Pure]
function array_values(array $array): array {}

/**
 * Counts all the values of an array
 * @link https://php.net/manual/en/function.array-count-values.php
 * @param array $array <p>
 * The array of values to count
 * </p>
 * @return array an associative array of values from input as
 * keys and their count as value.
 */
#[Pure]
function array_count_values(array $array): array {}

/**
 * (PHP 5 &gt;=5.5.0)<br/>
 * Return the values from a single column in the input array
 * @link https://secure.php.net/manual/en/function.array-column.php
 * @param array $array <p>A multi-dimensional array (record set) from which to pull a column of values.</p>
 * @param string|int|null $column_key <p>The column of values to return. This value may be the integer key of the column you wish to retrieve, or it may be the string key name for an associative array. It may also be NULL to return complete arrays (useful together with index_key to reindex the array).</p>
 * @param string|int|null $index_key [optional] <p>The column to use as the index/keys for the returned array. This value may be the integer key of the column, or it may be the string key name.</p>
 * @return array Returns an array of values representing a single column from the input array.
 * @since 5.5
 */
#[Pure]
function array_column(array $array, string|int|null $column_key, string|int|null $index_key = null): array {}

/**
 * Return an array with elements in reverse order
 * @link https://php.net/manual/en/function.array-reverse.php
 * @param array $array <p>
 * The input array.
 * </p>
 * @param bool $preserve_keys [optional] <p>
 * If set to true keys are preserved.
 * </p>
 * @return array the reversed array.
 * @meta
 */
#[Pure]
function array_reverse(array $array, bool $preserve_keys = false): array {}

/**
 * Iteratively reduce the array to a single value using a callback function
 * @link https://php.net/manual/en/function.array-reduce.php
 * @param array $array <p>
 * The input array.
 * </p>
 * @param callable $callback <p>
 * The callback function. Signature is <pre>callback ( mixed $carry , mixed $item ) : mixed</pre>
 * <blockquote>mixed <var>$carry</var> <p>The return value of the previous iteration; on the first iteration it holds the value of <var>$initial</var>.</p></blockquote>
 * <blockquote>mixed <var>$item</var> <p>Holds the current iteration value of the <var>$input</var></p></blockquote>
 * </p>
 * @param mixed $initial [optional] <p>
 * If the optional initial is available, it will
 * be used at the beginning of the process, or as a final result in case
 * the array is empty.
 * </p>
 * @return mixed the resulting value.
 * <p>
 * If the array is empty and initial is not passed,
 * array_reduce returns null.
 * </p>
 * <br/>
 * <p>
 * Example use:
 * <blockquote><pre>array_reduce(['2', '3', '4'], function($ax, $dx) { return $ax . ", {$dx}"; }, '1')  // Returns '1, 2, 3, 4'</pre></blockquote>
 * <blockquote><pre>array_reduce(['2', '3', '4'], function($ax, $dx) { return $ax + (int)$dx; }, 1)  // Returns 10</pre></blockquote>
 * <br/>
 * </p>
 * @meta
 */
function array_reduce(array $array, callable $callback, mixed $initial = null): mixed {}

/**
 * Pad array to the specified length with a value
 * @link https://php.net/manual/en/function.array-pad.php
 * @param array $array <p>
 * Initial array of values to pad.
 * </p>
 * @param int $length <p>
 * New size of the array.
 * </p>
 * @param mixed $value <p>
 * Value to pad if input is less than
 * pad_size.
 * </p>
 * @return array a copy of the input padded to size specified
 * by pad_size with value
 * pad_value. If pad_size is
 * positive then the array is padded on the right, if it's negative then
 * on the left. If the absolute value of pad_size is less than or equal to
 * the length of the input then no padding takes place.
 */
#[Pure]
function array_pad(array $array, int $length, mixed $value): array {}

/**
 * Exchanges all keys with their associated values in an array
 * @link https://php.net/manual/en/function.array-flip.php
 * @param int[]|string[] $array <p>
 * An array of key/value pairs to be flipped.
 * </p>
 * @return int[]|string[] Returns the flipped array.
 */
#[Pure]
function array_flip(array $array): array {}

/**
 * Changes the case of all keys in an array
 * @link https://php.net/manual/en/function.array-change-key-case.php
 * @param array $array <p>
 * The array to work on
 * </p>
 * @param int $case <p>
 * Either CASE_UPPER or
 * CASE_LOWER (default)
 * </p>
 * @return array an array with its keys lower or uppercased
 * @meta
 */
#[Pure]
function array_change_key_case(array $array, int $case = CASE_LOWER): array {}

/**
 * Pick one or more random keys out of an array
 * @link https://php.net/manual/en/function.array-rand.php
 * @param array $array <p>
 * The input array.
 * </p>
 * @param int $num [optional] <p>
 * Specifies how many entries you want to pick.
 * </p>
 * @return int|string|array If you are picking only one entry, array_rand
 * returns the key for a random entry. Otherwise, it returns an array
 * of keys for the random entries. This is done so that you can pick
 * random keys as well as values out of the array.
 */
function array_rand(array $array, int $num = 1): array|string|int {}

/**
 * Removes duplicate values from an array
 * @link https://php.net/manual/en/function.array-unique.php
 * @param array $array <p>
 * The input array.
 * </p>
 * @param int $flags [optional] <p>
 * The optional second parameter sort_flags
 * may be used to modify the sorting behavior using these values:
 * </p>
 * <p>
 * Sorting type flags:
 * </p><ul>
 * <li>
 * <b>SORT_REGULAR</b> - compare items normally
 * (don't change types)
 * </li>
 * <li>
 * <b>SORT_NUMERIC</b> - compare items numerically
 * </li>
 * <li>
 * <b>SORT_STRING</b> - compare items as strings
 * </li>
 * <li>
 * <b>SORT_LOCALE_STRING</b> - compare items as strings,
 * based on the current locale
 * </li>
 * </ul>
 * @return array the filtered array.
 * @meta
 */
#[Pure]
function array_unique(array $array, int $flags = SORT_STRING): array {}

/**
 * Computes the intersection of arrays
 * @link https://php.net/manual/en/function.array-intersect.php
 * @param array $array <p>
 * The array with main values to check.
 * </p>
 * @param array ...$arrays arrays to compare values against.
 * @return array an array containing all of the values in
 * array1 whose values exist in all of the parameters.
 * @meta
 */
#[Pure]
function array_intersect(array $array, #[PhpStormStubsElementAvailable(from: '5.3', to: '7.4')] $arrays, array ...$arrays): array {}

/**
 * Computes the intersection of arrays using keys for comparison
 * @link https://php.net/manual/en/function.array-intersect-key.php
 * @param array $array <p>
 * The array with main keys to check.
 * </p>
 * @param array ...$arrays
 * @return array an associative array containing all the values and keys of
 * array1 which have keys that are present in all
 * arguments.
 * @meta
 */
#[Pure]
function array_intersect_key(array $array, #[PhpStormStubsElementAvailable(from: '5.3', to: '7.4')] $arrays, array ...$arrays): array {}

/**
 * Computes the intersection of arrays using a callback function on the keys for comparison
 * @link https://php.net/manual/en/function.array-intersect-ukey.php
 * @param array $array <p>
 * Initial array for comparison of the arrays.
 * </p>
 * @param array $array2 <p>
 * First array to compare keys against.
 * </p>
 * @param callable $key_compare_func <p>
 * User supplied callback function to do the comparison.
 * </p>
 * @param ...$rest [optional]
 * @return array the values of array1 whose keys exist
 * in all the arguments.
 * @meta
 */
function array_intersect_ukey(
    array $array,
    #[PhpStormStubsElementAvailable(from: '5.3', to: '7.4')] array $array2,
    #[PhpStormStubsElementAvailable(from: '5.3', to: '7.4')] callable $key_compare_func,
    #[PhpStormStubsElementAvailable(from: '8.0')] ...$rest
): array {}

/**
 * Computes the intersection of arrays, compares data by a callback function
 * @link https://php.net/manual/en/function.array-uintersect.php
 * @param array $array <p>
 * The first array.
 * </p>
 * @param array $array2 <p>
 * The second array.
 * </p>
 * @param callable $data_compare_func <p>
 * The callback comparison function.
 * </p>
 * @param array ...$rest
 * <p>
 * The user supplied callback function is used for comparison.
 * It must return an integer less than, equal to, or greater than zero if
 * the first argument is considered to be respectively less than, equal
 * to, or greater than the second.
 * </p>
 * @return array an array containing all the values and keys of array1
 * that are present in all the arguments.
 * @meta
 */
function array_uintersect(
    array $array,
    #[PhpStormStubsElementAvailable(from: '5.3', to: '7.4')] array $array2,
    #[PhpStormStubsElementAvailable(from: '5.3', to: '7.4')] callable $data_compare_func,
    #[PhpStormStubsElementAvailable(from: '8.0')] ...$rest
): array {}

/**
 * Computes the intersection of arrays with additional index check
 * @link https://php.net/manual/en/function.array-intersect-assoc.php
 * @param array $array <p>
 * The array with main values to check.
 * </p>
 * @param array $arrays
 * @return array an associative array containing all the values and keys in
 * array1 that are present in all of the arguments.
 * @meta
 */
#[Pure]
function array_intersect_assoc(array $array, #[PhpStormStubsElementAvailable(from: '5.3', to: '7.4')] $arrays, array ...$arrays): array {}

/**
 * Computes the intersection of arrays with additional index check, compares data by a callback function
 * @link https://php.net/manual/en/function.array-uintersect-assoc.php
 * @param array $array <p>
 * The first array.
 * </p>
 * @param array $array2 <p>
 * The second array.
 * </p>
 * @param callable $data_compare_func <p>
 * For comparison is used the user supplied callback function.
 * It must return an integer less than, equal
 * to, or greater than zero if the first argument is considered to
 * be respectively less than, equal to, or greater than the
 * second.
 * </p>
 * @param array ...$rest
 * @return array an array containing all the values and keys of
 * array1 that are present in all the arguments.
 * @meta
 */
function array_uintersect_assoc(
    array $array,
    #[PhpStormStubsElementAvailable(from: '5.3', to: '7.4')] array $array2,
    #[PhpStormStubsElementAvailable(from: '5.3', to: '7.4')] callable $data_compare_func,
    #[PhpStormStubsElementAvailable(from: '8.0')] ...$rest
): array {}

/**
 * Computes the intersection of arrays with additional index check, compares indexes by a callback function
 * @link https://php.net/manual/en/function.array-intersect-uassoc.php
 * @param array $array <p>
 * Initial array for comparison of the arrays.
 * </p>
 * @param array $array2 <p>
 * First array to compare keys against.
 * </p>
 * @param callable $key_compare_func <p>
 * User supplied callback function to do the comparison.
 * </p>
 * @param array ...$rest
 * @return array the values of array1 whose values exist
 * in all of the arguments.
 * @meta
 */
function array_intersect_uassoc(
    array $array,
    #[PhpStormStubsElementAvailable(from: '5.3', to: '7.4')] array $array2,
    #[PhpStormStubsElementAvailable(from: '5.3', to: '7.4')] callable $key_compare_func,
    #[PhpStormStubsElementAvailable(from: '8.0')] ...$rest
): array {}

/**
 * Computes the intersection of arrays with additional index check, compares data and indexes by separate callback functions
 * @link https://php.net/manual/en/function.array-uintersect-uassoc.php
 * @param array $array <p>
 * The first array.
 * </p>
 * @param array $array2 <p>
 * The second array.
 * </p>
 * @param callable $data_compare_func <p>
 * For comparison is used the user supplied callback function.
 * It must return an integer less than, equal
 * to, or greater than zero if the first argument is considered to
 * be respectively less than, equal to, or greater than the
 * second.
 * </p>
 * @param callable $key_compare_func <p>
 * Key comparison callback function.
 * </p>
 * @param array ...$rest
 * @return array an array containing all the values and keys of
 * array1 that are present in all the arguments.
 * @meta
 */
#[Pure]
function array_uintersect_uassoc(
    array $array,
    #[PhpStormStubsElementAvailable(from: '5.3', to: '7.4')] array $array2,
    #[PhpStormStubsElementAvailable(from: '5.3', to: '7.4')] callable $data_compare_func,
    #[PhpStormStubsElementAvailable(from: '5.3', to: '7.4')] callable $key_compare_func,
    #[PhpStormStubsElementAvailable(from: '8.0')] ...$rest
): array {}

/**
 * Computes the difference of arrays
 * @link https://php.net/manual/en/function.array-diff.php
 * @param array $array <p>
 * The array to compare from
 * </p>
 * @param array ...$arrays
 * @return array an array containing all the entries from
 * array1 that are not present in any of the other arrays.
 * @meta
 */
#[Pure]
function array_diff(array $array, #[PhpStormStubsElementAvailable(from: '5.3', to: '7.4')] $arrays, array ...$arrays): array {}

/**
 * Computes the difference of arrays using keys for comparison
 * @link https://php.net/manual/en/function.array-diff-key.php
 * @param array $array <p>
 * The array to compare from
 * </p>
 * @param array $arrays <p>
 * An array to compare against
 * </p>
 * @return array an array containing all the values and keys from
 * array1 whose keys are not present in any of the
 * other arrays.
 * @meta
 */
#[Pure]
function array_diff_key(array $array, #[PhpStormStubsElementAvailable(from: '5.3', to: '7.4')] $arrays, array ...$arrays): array {}

/**
 * Computes the difference of arrays using a callback function on the keys for comparison
 * @link https://php.net/manual/en/function.array-diff-ukey.php
 * @param array $array <p>
 * The array to compare from
 * </p>
 * @param array $array2 <p>
 * An array to compare against
 * </p>
 * @param callable $key_compare_func <p>
 * callback function to use.
 * The callback function must return an integer less than, equal
 * to, or greater than zero if the first argument is considered to
 * be respectively less than, equal to, or greater than the second.
 * </p>
 * @param array ...$rest [optional]
 * @return array an array containing all the values and keys from
 * array1 that are not present in any of the other arrays.
 * @meta
 */
function array_diff_ukey(
    array $array,
    #[PhpStormStubsElementAvailable(from: '5.3', to: '7.4')] array $array2,
    #[PhpStormStubsElementAvailable(from: '5.3', to: '7.4')] callable $key_compare_func,
    #[PhpStormStubsElementAvailable(from: '8.0')] ...$rest,
): array {}

/**
 * Computes the difference of arrays by using a callback function for data comparison
 * @link https://php.net/manual/en/function.array-udiff.php
 * @param array $array <p>
 * The first array.
 * </p>
 * @param array $array2 <p>
 * The second array.
 * </p>
 * @param callable $data_compare_func <p>
 * The callback comparison function.
 * </p>
 * <p>
 * The user supplied callback function is used for comparison.
 * It must return an integer less than, equal to, or greater than zero if
 * the first argument is considered to be respectively less than, equal
 * to, or greater than the second.
 * </p>
 * @param array ...$rest [optional]
 * @return array an array containing all the values and keys of array1
 * that are not present in any of the other arguments.
 * @meta
 */
function array_udiff(
    array $array,
    #[PhpStormStubsElementAvailable(from: '5.3', to: '7.4')] array $array2,
    #[PhpStormStubsElementAvailable(from: '5.3', to: '7.4')] callable $data_compare_func,
    #[PhpStormStubsElementAvailable(from: '8.0')] ...$rest,
): array {}

/**
 * Computes the difference of arrays with additional index check
 * @link https://php.net/manual/en/function.array-diff-assoc.php
 * @param array $array <p>
 * The array to compare from
 * </p>
 * @param array $arrays <p>
 * An array to compare against
 * </p>
 * @return array an array containing all the values from
 * array1 that are not present in any of the other arrays.
 * @meta
 */
#[Pure]
function array_diff_assoc(
    array $array,
    #[PhpStormStubsElementAvailable(from: '5.3', to: '7.4')] $arrays,
    array ...$arrays
): array {}

/**
 * Computes the difference of arrays with additional index check, compares data by a callback function
 * @link https://php.net/manual/en/function.array-udiff-assoc.php
 * @param array $array <p>
 * The first array.
 * </p>
 * @param array $array2 <p>
 * The second array.
 * </p>
 * @param callable $data_compare_func <p>
 * The callback comparison function.
 * </p>
 * <p>
 * The user supplied callback function is used for comparison.
 * It must return an integer less than, equal to, or greater than zero if
 * the first argument is considered to be respectively less than, equal
 * to, or greater than the second.
 * </p>
 * @param array ...$rest [optional]
 * @return array array_udiff_assoc returns an array
 * containing all the values and keys from array1
 * that are not present in any of the other arguments.
 * Note that the keys are used in the comparison unlike
 * array_diff and array_udiff.
 * The comparison of arrays' data is performed by using an user-supplied
 * callback. In this aspect the behaviour is opposite to the behaviour of
 * array_diff_assoc which uses internal function for
 * comparison.
 * @meta
 */
function array_udiff_assoc(
    array $array,
    #[PhpStormStubsElementAvailable(from: '5.3', to: '7.4')] array $array2,
    #[PhpStormStubsElementAvailable(from: '5.3', to: '7.4')] callable $data_compare_func,
    #[PhpStormStubsElementAvailable(from: '8.0')] ...$rest,
): array {}

/**
 * Computes the difference of arrays with additional index check which is performed by a user supplied callback function
 * @link https://php.net/manual/en/function.array-diff-uassoc.php
 * @param array $array <p>
 * The array to compare from
 * </p>
 * @param array $array2 <p>
 * An array to compare against
 * </p>
 * @param callable $key_compare_func <p>
 * callback function to use.
 * The callback function must return an integer less than, equal
 * to, or greater than zero if the first argument is considered to
 * be respectively less than, equal to, or greater than the second.
 * </p>
 * @param array ...$rest [optional]
 * @return array an array containing all the values and keys from
 * array1 that are not present in any of the other arrays.
 * @meta
 */
function array_diff_uassoc(
    array $array,
    #[PhpStormStubsElementAvailable(from: '5.3', to: '7.4')] array $array2,
    #[PhpStormStubsElementAvailable(from: '5.3', to: '7.4')] callable $key_compare_func,
    #[PhpStormStubsElementAvailable(from: '8.0')] ...$rest,
): array {}

/**
 * Computes the difference of arrays with additional index check, compares data and indexes by a callback function
 * @link https://php.net/manual/en/function.array-udiff-uassoc.php
 * @param array $array <p>
 * The first array.
 * </p>
 * @param array $array2 <p>
 * The second array.
 * </p>
 * @param callable $data_compare_func <p>
 * The callback comparison function.
 * </p>
 * <p>
 * The user supplied callback function is used for comparison.
 * It must return an integer less than, equal to, or greater than zero if
 * the first argument is considered to be respectively less than, equal
 * to, or greater than the second.
 * </p>
 * <p>
 * The comparison of arrays' data is performed by using an user-supplied
 * callback : data_compare_func. In this aspect
 * the behaviour is opposite to the behaviour of
 * array_diff_assoc which uses internal function for
 * comparison.
 * </p>
 * @param callable $key_compare_func <p>
 * The comparison of keys (indices) is done also by the callback function
 * key_compare_func. This behaviour is unlike what
 * array_udiff_assoc does, since the latter compares
 * the indices by using an internal function.
 * </p>
 * @param array ...$rest [optional]
 * @return array an array containing all the values and keys from
 * array1 that are not present in any of the other
 * arguments.
 * @meta
 */
function array_udiff_uassoc(
    array $array,
    #[PhpStormStubsElementAvailable(from: '5.3', to: '7.4')] array $array2,
    #[PhpStormStubsElementAvailable(from: '5.3', to: '7.4')] callable $data_compare_func,
    #[PhpStormStubsElementAvailable(from: '5.3', to: '7.4')] callable $key_compare_func,
    #[PhpStormStubsElementAvailable(from: '8.0')] ...$rest
): array {}

/**
 * Calculate the sum of values in an array
 * @link https://php.net/manual/en/function.array-sum.php
 * @param array $array <p>
 * The input array.
 * </p>
 * @return int|float the sum of values as an integer or float.
 */
#[Pure]
function array_sum(array $array): int|float {}

/**
 * Calculate the product of values in an array
 * @link https://php.net/manual/en/function.array-product.php
 * @param array $array <p>
 * The array.
 * </p>
 * @return int|float the product as an integer or float.
 */
#[Pure]
function array_product(array $array): int|float {}

/**
 * Iterates over each value in the <b>array</b>
 * passing them to the <b>callback</b> function.
 * If the <b>callback</b> function returns true, the
 * current value from <b>array</b> is returned into
 * the result array. Array keys are preserved.
 * @link https://php.net/manual/en/function.array-filter.php
 * @param array $array <p>
 * The array to iterate over
 * </p>
 * @param callable|null $callback [optional] <p>
 * The callback function to use
 * </p>
 * <p>
 * If no callback is supplied, all entries of
 * input equal to false (see
 * converting to
 * boolean) will be removed.
 * </p>
 * @param int $mode [optional] <p>
 * Flag determining what arguments are sent to <i>callback</i>:
 * </p><ul>
 * <li>
 * <b>ARRAY_FILTER_USE_KEY</b> - pass key as the only argument
 * to <i>callback</i> instead of the value</span>
 * </li>
 * <li>
 * <b>ARRAY_FILTER_USE_BOTH</b> - pass both value and key as
 * arguments to <i>callback</i> instead of the value</span>
 * </li>
 * </ul>
 * @return array the filtered array.
 * @meta
 */
function array_filter(array $array, ?callable $callback, int $mode = 0): array {}

/**
 * Applies the callback to the elements of the given arrays
 * @link https://php.net/manual/en/function.array-map.php
 * @param callable|null $callback <p>
 * Callback function to run for each element in each array.
 * </p>
 * @param array $array <p>
 * An array to run through the callback function.
 * </p>
 * @param array ...$arrays
 * @return array an array containing all the elements of arr1
 * after applying the callback function to each one.
 * @meta
 */
function array_map(
    ?callable $callback,
    #[PhpStormStubsElementAvailable(from: '8.0')] array $array,
    #[PhpStormStubsElementAvailable(from: '5.3', to: '7.4')] $arrays,
    array ...$arrays
): array {}

/**
 * Split an array into chunks
 * @link https://php.net/manual/en/function.array-chunk.php
 * @param array $array <p>
 * The array to work on
 * </p>
 * @param int $length <p>
 * The size of each chunk
 * </p>
 * @param bool $preserve_keys [optional] <p>
 * When set to true keys will be preserved.
 * Default is false which will reindex the chunk numerically
 * </p>
 * @return array a multidimensional numerically indexed array, starting with zero,
 * with each dimension containing size elements.
 */
#[Pure]
function array_chunk(array $array, int $length, bool $preserve_keys = false): array {}

/**
 * Creates an array by using one array for keys and another for its values
 * @link https://php.net/manual/en/function.array-combine.php
 * @param array $keys <p>
 * Array of keys to be used. Illegal values for key will be
 * converted to string.
 * </p>
 * @param array $values <p>
 * Array of values to be used
 * </p>
 * @return array|false the combined array, false if the number of elements
 * for each array isn't equal or if the arrays are empty.
 * @meta
 */
#[Pure]
#[LanguageLevelTypeAware(["8.0" => "array"], default: "array|false")]
function array_combine(array $keys, array $values) {}

/**
 * Checks if the given key or index exists in the array
 * @link https://php.net/manual/en/function.array-key-exists.php
 * @param int|string $key <p>
 * Value to check.
 * </p>
 * @param array|ArrayObject $array <p>
 * An array with keys to check.
 * </p>
 * @return bool true on success or false on failure.
 */
#[Pure]
function array_key_exists($key, #[LanguageLevelTypeAware(["8.0" => "array"], default: "array|ArrayObject")] $array): bool {}

/**
 * Gets the first key of an array
 *
 * Get the first key of the given array without affecting the internal array pointer.
 *
 * @link https://secure.php.net/array_key_first
 * @param array $array An array
 * @return string|int|null Returns the first key of array if the array is not empty; NULL otherwise.
 * @since 7.3
 */
#[Pure]
function array_key_first(array $array): string|int|null {}

/**
 * Gets the last key of an array
 *
 * Get the last key of the given array without affecting the internal array pointer.
 *
 * @link https://secure.php.net/array_key_last
 * @param array $array An array
 * @return string|int|null Returns the last key of array if the array is not empty; NULL otherwise.
 * @since 7.3
 */
#[Pure]
function array_key_last(array $array): string|int|null {}

/**
 * @link https://secure.php.net/array_is_list
 * @param array $array An array
 * @return bool return true if the array keys are 0 .. count($array)-1 in that order.
 * For other arrays, it returns false. For non-arrays, it throws a TypeError.
 * @since 8.1
 */
#[Pure]
function array_is_list(array $array): bool {}

/**
 * Alias:
 * {@see current}
 * @link https://php.net/manual/en/function.pos.php
 * @param array|ArrayAccess $array
 * @return mixed
 */
#[Pure]
function pos(object|array $array): mixed {}

/**
 * Alias:
 * {@see \count}
 * @link https://php.net/manual/en/function.sizeof.php
 * @param array|Countable $value
 * @param int $mode [optional]
 * @return int<0, max>
 */
#[Pure]
function sizeof(Countable|array $value, int $mode = COUNT_NORMAL): int {}

/**
 * Checks if the given key or index exists in the array. The name of this function is array_key_exists() in PHP > 4.0.6.
 * @link https://php.net/manual/en/function.array-key-exists.php
 * @param int|string $key <p>
 * Value to check.
 * </p>
 * @param array $array <p>
 * An array with keys to check.
 * </p>
 * @return bool true on success or false on failure.
 */
#[Pure]
function key_exists($key, array $array): bool {}

/**
 * Checks if assertion is <b>FALSE</b>
 * @link https://php.net/manual/en/function.assert.php
 * @param Throwable|string|null $assertion <p>
 * The assertion.
 * In PHP 5, this must be either a string to be evaluated or a boolean to be tested.
 * In PHP 7, this may also be any expression that returns a value,
 * which will be executed and the result used to indicate whether the assertion succeeded or failed.<br/>
 * Since 7.2.0 using string is deprecated.
 * </p>
 * @param string $description [optional]
 * <p>An optional description that will be included in the failure message if the assertion fails.</p>
 * @return bool false if the assertion is false, true otherwise.
 */
function assert(
    mixed $assertion,
    #[PhpStormStubsElementAvailable(from: '7.0')] #[LanguageLevelTypeAware(['7.0' => 'Throwable|string|null'], default: 'string')] $description = null
): bool {}

/**
 * AssertionError is thrown when an assertion made via {@see assert()} fails.
 * @link https://php.net/manual/en/class.assertionerror.php
 * @since 7.0
 */
class AssertionError extends Error {}

/**
 * Set/get the various assert flags
 * @link https://php.net/manual/en/function.assert-options.php
 * @param int $option <p>
 * <table>
 * Assert Options
 * <tr valign="top">
 * <td>Option</td>
 * <td>INI Setting</td>
 * <td>Default value</td>
 * <td>Description</td>
 * </tr>
 * <tr valign="top">
 * <td>ASSERT_ACTIVE</td>
 * <td>assert.active</td>
 * <td>1</td>
 * <td>enable assert evaluation</td>
 * </tr>
 * <tr valign="top">
 * <td>ASSERT_WARNING</td>
 * <td>assert.warning</td>
 * <td>1</td>
 * <td>issue a PHP warning for each failed assertion</td>
 * </tr>
 * <tr valign="top">
 * <td>ASSERT_BAIL</td>
 * <td>assert.bail</td>
 * <td>0</td>
 * <td>terminate execution on failed assertions</td>
 * </tr>
 * <tr valign="top">
 * <td>ASSERT_QUIET_EVAL</td>
 * <td>assert.quiet_eval</td>
 * <td>0</td>
 * <td>
 * disable error_reporting during assertion expression
 * evaluation
 * </td>
 * </tr>
 * <tr valign="top">
 * <td>ASSERT_CALLBACK</td>
 * <td>assert.callback</td>
 * <td>null</td>
 * <td>Callback to call on failed assertions</td>
 * </tr>
 * </table>
 * </p>
 * @param mixed $value [optional] <p>
 * An optional new value for the option.
 * </p>
 * @return mixed The original setting of any option.
 */
function assert_options(int $option, mixed $value): mixed {}

/**
 * Compares two "PHP-standardized" version number strings
 * @link https://php.net/manual/en/function.version-compare.php
 * @param string $version1 <p>
 * First version number.
 * </p>
 * @param string $version2 <p>
 * Second version number.
 * </p>
 * @param string|null $operator [optional] <p>
 * If you specify the third optional operator
 * argument, you can test for a particular relationship. The
 * possible operators are: &lt;,
 * lt, &lt;=,
 * le, &gt;,
 * gt, &gt;=,
 * ge, ==,
 * =, eq,
 * !=, &lt;&gt;,
 * ne respectively.
 * </p>
 * <p>
 * This parameter is case-sensitive, so values should be lowercase.
 * </p>
 * @return int|bool By default, version_compare returns
 * -1 if the first version is lower than the second,
 * 0 if they are equal, and
 * 1 if the second is lower.
 * </p>
 * <p>
 * When using the optional operator argument, the
 * function will return true if the relationship is the one specified
 * by the operator, false otherwise.
 */
#[ExpectedValues([-1, 0, 1, false, true])]
function version_compare(
    string $version1,
    string $version2,
    #[ExpectedValues(values: [
               "<",
               "lt",
               "<=",
               "le",
               ">",
               "gt",
               ">=",
               "ge",
               "==",
               "=",
               "eq",
               "!=",
               "<>",
               "ne"
           ])] ?string $operator
): int|bool {}

/**
 * Convert a pathname and a project identifier to a System V IPC key
 * @link https://php.net/manual/en/function.ftok.php
 * @param string $filename <p>
 * Path to an accessible file.
 * </p>
 * @param string $project_id <p>
 * Project identifier. This must be a one character string.
 * </p>
 * @return int On success the return value will be the created key value, otherwise
 * -1 is returned.
 */
#[Pure(true)]
function ftok(string $filename, string $project_id): int {}

/**
 * Perform the rot13 transform on a string
 * @link https://php.net/manual/en/function.str-rot13.php
 * @param string $string <p>
 * The input string.
 * </p>
 * @return string the ROT13 version of the given string.
 */
#[Pure]
function str_rot13(string $string): string {}

/**
 * Retrieve list of registered filters
 * @link https://php.net/manual/en/function.stream-get-filters.php
 * @return list<string> an indexed array containing the name of all stream filters
 * available.
 */
#[Pure(true)]
function stream_get_filters(): array {}

/**
 * Check if a stream is a TTY
 * @link https://php.net/manual/en/function.stream-isatty.php
 * @param resource $stream
 * @return bool
 * @since 7.2
 */
#[Pure]
function stream_isatty($stream): bool {}

/**
 * Register a user defined stream filter
 * @link https://php.net/manual/en/function.stream-filter-register.php
 * @param string $filter_name <p>
 * The filter name to be registered.
 * </p>
 * @param string $class <p>
 * To implement a filter, you need to define a class as an extension of
 * php_user_filter with a number of member functions
 * as defined below. When performing read/write operations on the stream
 * to which your filter is attached, PHP will pass the data through your
 * filter (and any other filters attached to that stream) so that the
 * data may be modified as desired. You must implement the methods
 * exactly as described below - doing otherwise will lead to undefined
 * behaviour.
 * </p>
 * intfilter
 * resourcein
 * resourceout
 * intconsumed
 * boolclosing
 * <p>
 * This method is called whenever data is read from or written to
 * the attached stream (such as with fread or fwrite).
 * in is a resource pointing to a bucket brigade
 * which contains one or more bucket objects containing data to be filtered.
 * out is a resource pointing to a second bucket brigade
 * into which your modified buckets should be placed.
 * consumed, which must always
 * be declared by reference, should be incremented by the length of the data
 * which your filter reads in and alters. In most cases this means you will
 * increment consumed by $bucket->datalen
 * for each $bucket. If the stream is in the process of closing
 * (and therefore this is the last pass through the filterchain),
 * the closing parameter will be set to true.
 * The filter method must return one of
 * three values upon completion.
 * <tr valign="top">
 * <td>Return Value</td>
 * <td>Meaning</td>
 * </tr>
 * <tr valign="top">
 * <td>PSFS_PASS_ON</td>
 * <td>
 * Filter processed successfully with data available in the
 * out bucket brigade.
 * </td>
 * </tr>
 * <tr valign="top">
 * <td>PSFS_FEED_ME</td>
 * <td>
 * Filter processed successfully, however no data was available to
 * return. More data is required from the stream or prior filter.
 * </td>
 * </tr>
 * <tr valign="top">
 * <td>PSFS_ERR_FATAL (default)</td>
 * <td>
 * The filter experienced an unrecoverable error and cannot continue.
 * </td>
 * </tr>
 * </p>
 * boolonCreate
 * This method is called during instantiation of the filter class
 * object. If your filter allocates or initializes any other resources
 * (such as a buffer), this is the place to do it. Your implementation of
 * this method should return false on failure, or true on success.
 * When your filter is first instantiated, and
 * yourfilter-&gt;onCreate() is called, a number of properties
 * will be available as shown in the table below.
 * <p>
 * <tr valign="top">
 * <td>Property</td>
 * <td>Contents</td>
 * </tr>
 * <tr valign="top">
 * <td>FilterClass-&gt;filtername</td>
 * <td>
 * A string containing the name the filter was instantiated with.
 * Filters may be registered under multiple names or under wildcards.
 * Use this property to determine which name was used.
 * </td>
 * </tr>
 * <tr valign="top">
 * <td>FilterClass-&gt;params</td>
 * <td>
 * The contents of the params parameter passed
 * to stream_filter_append
 * or stream_filter_prepend.
 * </td>
 * </tr>
 * <tr valign="top">
 * <td>FilterClass-&gt;stream</td>
 * <td>
 * The stream resource being filtered. Maybe available only during
 * filter calls when the
 * closing parameter is set to false.
 * </td>
 * </tr>
 * </p>
 * voidonClose
 * <p>
 * This method is called upon filter shutdown (typically, this is also
 * during stream shutdown), and is executed after
 * the flush method is called. If any resources
 * were allocated or initialized during onCreate()
 * this would be the time to destroy or dispose of them.
 * </p>
 * @return bool true on success or false on failure.
 * <p>
 * stream_filter_register will return false if the
 * filtername is already defined.
 * </p>
 */
function stream_filter_register(string $filter_name, string $class): bool {}

/**
 * Return a bucket object from the brigade for operating on
 * @link https://php.net/manual/en/function.stream-bucket-make-writeable.php
 * @param resource $brigade
 * @return object|null
 */
function stream_bucket_make_writeable($brigade): ?object {}

/**
 * Prepend bucket to brigade
 * @link https://php.net/manual/en/function.stream-bucket-prepend.php
 * @param resource $brigade
 * @param object $bucket
 * @return void
 */
function stream_bucket_prepend($brigade, object $bucket): void {}

/**
 * Append bucket to brigade
 * @link https://php.net/manual/en/function.stream-bucket-append.php
 * @param resource $brigade
 * @param object $bucket
 * @return void
 */
function stream_bucket_append($brigade, object $bucket): void {}

/**
 * Create a new bucket for use on the current stream
 * @link https://php.net/manual/en/function.stream-bucket-new.php
 * @param resource $stream
 * @param string $buffer
 * @return object
 */
function stream_bucket_new($stream, string $buffer): object {}

/**
 * Add URL rewriter values
 * @link https://php.net/manual/en/function.output-add-rewrite-var.php
 * @param string $name <p>
 * The variable name.
 * </p>
 * @param string $value <p>
 * The variable value.
 * </p>
 * @return bool true on success or false on failure.
 */
function output_add_rewrite_var(string $name, string $value): bool {}

/**
 * Reset URL rewriter values
 * <table>
 * <thead>
 * <tr>
 * <th>Version</th>
 * <th>Description</th>
 * </tr>
 *
 * </thead>
 *
 * <tbody>
 * <tr>
 * <td>7.1.0</td>
 * <td>
 * Before PHP 7.1.0, rewrite vars set by <span class="function"><a href="function.output-add-rewrite-var.php" class="function">output_add_rewrite_var()</a></span>
 * use the same Session module trans sid output buffer. Since PHP 7.1.0,
 * dedicated output buffer is used and {@see output_reset_rewrite_vars()}
 * only removes rewrite vars defined by {@see output_add_rewrite_var()}.
 * </td>
 * </tr>
 *
 * </tbody>
 *
 * </table>
 *
 * @link https://php.net/manual/en/function.output-reset-rewrite-vars.php
 * @return bool true on success or false on failure.
 */
function output_reset_rewrite_vars(): bool {}

/**
 * Returns directory path used for temporary files
 * @link https://php.net/manual/en/function.sys-get-temp-dir.php
 * @return string the path of the temporary directory.
 * @since 5.2.1
 */
function sys_get_temp_dir(): string {}

/**
 * Get the contents of the realpath cache.
 * @link https://php.net/manual/en/function.realpath-cache-get.php
 * @return array Returns an array of realpath cache entries. The keys are
 * original path entries, and the values are arrays of data items,
 * containing the resolved path, expiration date, and other options kept in
 * the cache.
 * @since 5.3.2
 */
#[Pure(true)]
function realpath_cache_get(): array {}

/**
 * Get the amount of memory used by the realpath cache.
 * @link https://php.net/manual/en/function.realpath-cache-size.php
 * @return int Returns how much memory realpath cache is using.
 * @since 5.3.2
 */
#[Pure(true)]
function realpath_cache_size(): int {}

/**
 * It returns the same result as (array) $object, with the
 * exception that it ignores overloaded array casts, such as used by
 * ArrayObject.
 * @param object $object
 * @return array returns the mangled object properties
 * @since 7.4
 */
function get_mangled_object_vars(object $object): array {}

/**
 * Get the type or object name of a variable
 *
 * @param mixed $value The variable being type checked.
 * @return string Possibles values for the returned string are:
 *  - "int"
 *  - "float"
 *  - "bool"
 *  - "string"
 *  - "array"
 *  - "null"
 *  - A class name for named classes
 *  - "class@anonymous" for an anonymous classes
 *  - "resource (xxx)" for any resources where "xxx" is a name of resource
 *  - "resource (closed)" for closed resources
 * @since 8.0
 */
#[Pure]
function get_debug_type(mixed $value): string {}

/**
 * A more obvious and type-safe form of "(int) $resource"
 *
 * @param resource $resource
 * @return int
 * @since 8.0
 */
#[Pure]
function get_resource_id($resource): int {}
