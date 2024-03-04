<?php

use JetBrains\PhpStorm\ExpectedValues;

/**
 * Read a size of file created by applying a binary diff
 * @link https://www.php.net/manual/en/function.xdiff-file-bdiff-size.php
 * @param string $file The path to the binary patch created by xdiff_string_bdiff() or xdiff_string_rabdiff() function
 * @return int the size of file that would be created.
 */
function xdiff_file_bdiff_size(string $file): int {}

/**
 * Make binary diff of two files
 * @link https://www.php.net/manual/en/function.xdiff-file-bdiff.php
 * @param string $old_file Path to the first file. This file acts as "old" file.
 * @param string $new_file Path to the second file. This file acts as "new" file.
 * @param string $dest Path of the resulting patch file. Resulting file contains differences between
 * "old" and "new" files. It is in binary format and is human-unreadable.
 * @return bool true on success or false on failure.
 */
function xdiff_file_bdiff(string $old_file, string $new_file, string $dest): bool {}

/**
 * Patch a file with a binary diff
 * @link https://www.php.net/manual/en/function.xdiff-file-bpatch.php
 * @param string $file The original file.
 * @param string $patch The binary patch file.
 * @param string $dest Path of the resulting file.
 * @return bool true on success or false on failure.
 */
function xdiff_file_bpatch(string $file, string $patch, string $dest): bool {}

/**
 * Alias of xdiff_file_bdiff
 * @link https://www.php.net/manual/en/function.xdiff-file-diff-binary.php
 * @param string $old_file Path to the first file. This file acts as "old" file.
 * @param string $new_file Path to the second file. This file acts as "new" file.
 * @param string $dest Path of the resulting patch file. Resulting file contains differences between
 * "old" and "new" files. It is in binary format and is human-unreadable.
 * @return bool true on success or false on failure.
 */
function xdiff_file_diff_binary(string $old_file, string $new_file, string $dest): bool {}

/**
 * Make unified diff of two files
 * @link https://www.php.net/manual/en/function.xdiff-file-diff.php
 * @param string $old_file Path to the first file. This file acts as "old" file.
 * @param string $new_file Path to the second file. This file acts as "new" file.
 * @param string $dest Path of the resulting patch file.
 * @param int $context Indicates how many lines of context you want to include in diff result.
 * @param bool $minimal Set this parameter to true if you want to minimalize size of the result (can take a long time).
 * @return bool true on success or false on failure.
 */
function xdiff_file_diff(
    string $old_file,
    string $new_file,
    string $dest,
    int $context = 3,
    bool $minimal = false
): bool {}

/**
 * Merge 3 files into one
 * @link https://www.php.net/manual/en/function.xdiff-file-merge3.php
 * @param string $old_file Path to the first file. It acts as "old" file.
 * @param string $new_file1 Path to the second file. It acts as modified version of old_file.
 * @param string $new_file2 Path to the third file. It acts as modified version of old_file.
 * @param string $dest Path of the resulting file, containing merged changed from both new_file1 and new_file2.
 * @return bool|string true if merge was successful, string with rejected chunks if it was not or false if an internal
 * error happened.
 */
function xdiff_file_merge3(string $old_file, string $new_file1, string $new_file2, string $dest) {}

/**
 * Alias of xdiff_file_bpatch
 * @link https://www.php.net/manual/en/function.xdiff-file-patch-binary.php
 * @param string $file The original file.
 * @param string $patch The binary patch file.
 * @param string $dest Path of the resulting file.
 * @return bool true on success or false on failure.
 */
function xdiff_file_patch_binary(string $file, string $patch, string $dest): bool {}

/**
 * Patch a file with an unified diff
 * @link https://www.php.net/manual/en/function.xdiff-file-patch.php
 * @param string $file The original file.
 * @param string $patch The unified patch file. It has to be created using xdiff_string_diff(), xdiff_file_diff()
 * functions or compatible tools.
 * @param string $dest Path of the resulting file.
 * @param int $flags Can be either XDIFF_PATCH_NORMAL (default mode, normal patch) or XDIFF_PATCH_REVERSE
 * (reversed patch). Starting from version 1.5.0, you can also use binary OR to enable XDIFF_PATCH_IGNORESPACE flag.
 * @return bool|string false if an internal error happened, string with rejected chunks if patch couldn't be applied
 * or true if patch has been successfully applied.
 */
function xdiff_file_patch(string $file, string $patch, string $dest, #[ExpectedValues([XDIFF_PATCH_NORMAL|XDIFF_PATCH_REVERSE|XDIFF_PATCH_IGNORESPACE])] int $flags = XDIFF_PATCH_NORMAL) {}

/**
 * Make binary diff of two files using the Rabin's polynomial fingerprinting algorithm
 * @link https://www.php.net/manual/en/function.xdiff-file-rabdiff.php
 * @param string $old_file Path to the first file. This file acts as "old" file.
 * @param string $new_file Path to the second file. This file acts as "new" file.
 * @param string $dest Path of the resulting patch file. Resulting file contains differences between "old"
 * and "new" files. It is in binary format and is human-unreadable.
 * @return bool true on success or false on failure.
 */
function xdiff_file_rabdiff(string $old_file, string $new_file, string $dest): bool {}

/**
 * Read a size of file created by applying a binary diff
 * @link https://www.php.net/manual/en/function.xdiff-string-bdiff-size.php
 * @param string $patch The binary patch created by xdiff_string_bdiff() or xdiff_string_rabdiff() function.
 * @return int the size of file that would be created.
 */
function xdiff_string_bdiff_size(string $patch): int {}

/**
 * Make binary diff of two strings
 * @link https://www.php.net/manual/en/function.xdiff-string-bdiff.php
 * @param string $old_data First string with binary data. It acts as "old" data.
 * @param string $new_data Second string with binary data. It acts as "new" data.
 * @return string|false string with binary diff containing differences between "old" and "new" data or false
 * if an internal error occurred.
 */
function xdiff_string_bdiff(string $old_data, string $new_data) {}

/**
 * Patch a string with a binary diff
 * @link https://www.php.net/manual/en/function.xdiff-string-bpatch.php
 * @param string $str The original binary string.
 * @param string $patch The binary patch string.
 * @return string|false the patched string, or false on error.
 */
function xdiff_string_bpatch(string $str, string $patch) {}

/**
 * Alias of xdiff_string_bdiff
 * @link https://www.php.net/manual/en/function.xdiff-string-diff-binary.php
 * @param string $old_data First string with binary data. It acts as "old" data.
 * @param string $new_data Second string with binary data. It acts as "new" data.
 * @return string|false string with result or false if an internal error happened.
 */
function xdiff_string_diff_binary(string $old_data, string $new_data) {}

/**
 * Make unified diff of two strings
 * @link https://www.php.net/manual/en/function.xdiff-string-diff.php
 * @param string $old_data First string with data. It acts as "old" data.
 * @param string $new_data Second string with data. It acts as "new" data.
 * @param int $context Indicates how many lines of context you want to include in the diff result.
 * @param bool $minimal Set this parameter to true if you want to minimalize the size of the result
 * (can take a long time).
 * @return string|false string with resulting diff or false if an internal error happened.
 */
function xdiff_string_diff(string $old_data, string $new_data, int $context = 3, bool $minimal = false) {}

/**
 * Merge 3 strings into one
 * @link https://www.php.net/manual/en/function.xdiff-string-merge3.php
 * @param string $old_data First string with data. It acts as "old" data.
 * @param string $new_data1 Second string with data. It acts as modified version of old_data.
 * @param string $new_data2 Third string with data. It acts as modified version of old_data.
 * @param ?string $error If provided then rejected parts are stored inside this variable.
 * @return bool|string the merged string, false if an internal error happened, or true if merged string is empty.
 */
function xdiff_string_merge3(string $old_data, string $new_data1, string $new_data2, ?string &$error) {}

/**
 * Alias of xdiff_string_bpatch
 * @link https://www.php.net/manual/en/function.xdiff-string-patch-binary.php
 * @param string $str The original binary string.
 * @param string $patch The binary patch string.
 * @return string|false the patched string, or false on error.
 */
function xdiff_string_patch_binary(string $str, string $patch) {}

/**
 * Patch a string with an unified diff
 * @link https://www.php.net/manual/en/function.xdiff-string-patch.php
 * @param string $str The original string.
 * @param string $patch The unified patch string. It has to be created using xdiff_string_diff(), xdiff_file_diff()
 * functions or compatible tools.
 * @param ?int $flags flags can be either XDIFF_PATCH_NORMAL (default mode, normal patch) or XDIFF_PATCH_REVERSE
 * (reversed patch). Starting from version 1.5.0, you can also use binary OR to enable XDIFF_PATCH_IGNORESPACE flag.
 * @param ?string $error If provided then rejected parts are stored inside this variable.
 * @return string|false the patched string, or false on error.
 */
function xdiff_string_patch(string $str, string $patch, #[ExpectedValues([XDIFF_PATCH_NORMAL|XDIFF_PATCH_REVERSE|XDIFF_PATCH_IGNORESPACE])] ?int $flags, ?string &$error) {}

/**
 * Make binary diff of two strings using the Rabin's polynomial fingerprinting algorithm
 * @link https://www.php.net/manual/en/function.xdiff-string-rabdiff.php
 * @param string $old_data First string with binary data. It acts as "old" data.
 * @param string $new_data Second string with binary data. It acts as "new" data.
 * @return string|false string with binary diff containing differences between "old" and "new" data or false if an internal error occurred.
 */
function xdiff_string_rabdiff(string $old_data, string $new_data) {}

define('XDIFF_PATCH_NORMAL', 0);
define('XDIFF_PATCH_REVERSE', 0);
define('XDIFF_PATCH_IGNORESPACE', 0);
