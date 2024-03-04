<?php

// Start of readline v.5.5.3-1ubuntu2.1
use JetBrains\PhpStorm\ArrayShape;

/**
 * Reads a line
 * @link https://php.net/manual/en/function.readline.php
 * @param string|null $prompt [optional] <p>
 * You may specify a string with which to prompt the user.
 * </p>
 * @return string|false a single string from the user. The line returned has the ending newline removed.
 * If there is no more data to read, then FALSE is returned.
 */
function readline(?string $prompt): string|false {}

/**
 * Gets/sets various internal readline variables
 * @link https://php.net/manual/en/function.readline-info.php
 * @param string|null $var_name [optional] <p>
 * A variable name.
 * </p>
 * @param string $value [optional] <p>
 * If provided, this will be the new value of the setting.
 * </p>
 * @return mixed If called with no parameters, this function returns an array of
 * values for all the setting readline uses. The elements will
 * be indexed by the following values: done, end, erase_empty_line,
 * library_version, line_buffer, mark, pending_input, point, prompt,
 * readline_name, and terminal_name.
 * </p>
 * <p>
 * If called with one or two parameters, the old value is returned.
 */
#[ArrayShape([
    'line_buffer' => 'string',
    'point' => 'int',
    'end' => 'int',
    'mark' => 'int',
    'done' => 'int',
    'pending_input' => 'int',
    'prompt' => 'string',
    'terminal_name' => 'string',
    'completion_append_character' => 'string',
    'completion_suppress_append' => 'bool',
    'erase_empty_line' => 'int',
    'library_version' => 'string',
    'readline_name' => 'string',
    'attempted_completion_over' => 'int',
])]
function readline_info(?string $var_name, $value): mixed {}

/**
 * Adds a line to the history
 * @link https://php.net/manual/en/function.readline-add-history.php
 * @param string $prompt <p>
 * The line to be added in the history.
 * </p>
 * @return bool <b>TRUE</b> on success or <b>FALSE</b> on failure.
 */
function readline_add_history(string $prompt): bool {}

/**
 * Clears the history
 * @link https://php.net/manual/en/function.readline-clear-history.php
 * @return bool <b>TRUE</b> on success or <b>FALSE</b> on failure.
 */
function readline_clear_history(): bool {}

/**
 * Lists the history
 * @link https://php.net/manual/en/function.readline-list-history.php
 * @return array an array of the entire command line history. The elements are
 * indexed by integers starting at zero.
 */
function readline_list_history(): array {}

/**
 * Reads the history
 * @link https://php.net/manual/en/function.readline-read-history.php
 * @param string|null $filename [optional] <p>
 * Path to the filename containing the command history.
 * </p>
 * @return bool <b>TRUE</b> on success or <b>FALSE</b> on failure.
 */
function readline_read_history(?string $filename): bool {}

/**
 * Writes the history
 * @link https://php.net/manual/en/function.readline-write-history.php
 * @param string|null $filename [optional] <p>
 * Path to the saved file.
 * </p>
 * @return bool <b>TRUE</b> on success or <b>FALSE</b> on failure.
 */
function readline_write_history(?string $filename): bool {}

/**
 * Registers a completion function
 * @link https://php.net/manual/en/function.readline-completion-function.php
 * @param callable $callback <p>
 * You must supply the name of an existing function which accepts a
 * partial command line and returns an array of possible matches.
 * </p>
 * @return bool <b>TRUE</b> on success or <b>FALSE</b> on failure.
 */
function readline_completion_function(callable $callback): bool {}

/**
 * Initializes the readline callback interface and terminal, prints the prompt and returns immediately
 * @link https://php.net/manual/en/function.readline-callback-handler-install.php
 * @param string $prompt <p>
 * The prompt message.
 * </p>
 * @param callable $callback <p>
 * The <i>callback</i> function takes one parameter; the
 * user input returned.
 * </p>
 * @return bool <b>TRUE</b> on success or <b>FALSE</b> on failure.
 */
function readline_callback_handler_install(string $prompt, callable $callback): bool {}

/**
 * Reads a character and informs the readline callback interface when a line is received
 * @link https://php.net/manual/en/function.readline-callback-read-char.php
 * @return void No value is returned.
 */
function readline_callback_read_char(): void {}

/**
 * Removes a previously installed callback handler and restores terminal settings
 * @link https://php.net/manual/en/function.readline-callback-handler-remove.php
 * @return bool <b>TRUE</b> if a previously installed callback handler was removed, or
 * <b>FALSE</b> if one could not be found.
 */
function readline_callback_handler_remove(): bool {}

/**
 * Redraws the display
 * @link https://php.net/manual/en/function.readline-redisplay.php
 * @return void No value is returned.
 */
function readline_redisplay(): void {}

/**
 * Inform readline that the cursor has moved to a new line
 * @link https://php.net/manual/en/function.readline-on-new-line.php
 * @return void No value is returned.
 */
function readline_on_new_line(): void {}

define('READLINE_LIB', "readline");

// End of readline v.5.5.3-1ubuntu2.1
