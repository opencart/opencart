<?php

// Start of Core v.5.3.6-13ubuntu3.2
use JetBrains\PhpStorm\ArrayShape;
use JetBrains\PhpStorm\Deprecated;
use JetBrains\PhpStorm\Internal\LanguageLevelTypeAware;
use JetBrains\PhpStorm\Internal\PhpStormStubsElementAvailable;
use JetBrains\PhpStorm\Pure;

/**
 * Gets the version of the current Zend engine
 * @link https://php.net/manual/en/function.zend-version.php
 * @return string the Zend Engine version number, as a string.
 */
#[Pure]
function zend_version(): string {}

/**
 * Returns the number of arguments passed to the function
 * @link https://php.net/manual/en/function.func-num-args.php
 * @return int the number of arguments passed into the current user-defined
 * function.
 */
#[Pure]
function func_num_args(): int {}

/**
 * Return an item from the argument list
 * @link https://php.net/manual/en/function.func-get-arg.php
 * @param int $position <p>
 * The argument offset. Function arguments are counted starting from
 * zero.
 * </p>
 * @return mixed|false the specified argument, or false on error.
 */
#[Pure]
function func_get_arg(int $position): mixed {}

/**
 * Returns an array comprising a function's argument list
 * @link https://php.net/manual/en/function.func-get-args.php
 * @return array an array in which each element is a copy of the corresponding
 * member of the current user-defined function's argument list.
 */
#[Pure]
function func_get_args(): array {}

/**
 * Get string length
 * @link https://php.net/manual/en/function.strlen.php
 * @param string $string <p>
 * The string being measured for length.
 * </p>
 * @return int<0,max> The length of the <i>string</i> on success,
 * and 0 if the <i>string</i> is empty.
 */
#[Pure]
function strlen(string $string): int {}

/**
 * Binary safe string comparison
 * @link https://php.net/manual/en/function.strcmp.php
 * @param string $string1 <p>
 * The first string.
 * </p>
 * @param string $string2 <p>
 * The second string.
 * </p>
 * @return int less 0 if <i>str1</i> is less than
 * <i>str2</i>; &gt; 0 if <i>str1</i>
 * is greater than <i>str2</i>, and 0 if they are
 * equal.
 */
#[Pure]
function strcmp(string $string1, string $string2): int {}

/**
 * Binary safe string comparison of the first n characters
 * @link https://php.net/manual/en/function.strncmp.php
 * @param string $string1 <p>
 * The first string.
 * </p>
 * @param string $string2 <p>
 * The second string.
 * </p>
 * @param int $length <p>
 * Number of characters to use in the comparison.
 * </p>
 * @return int less 0 if <i>str1</i> is less than
 * <i>str2</i>; &gt; 0 if <i>str1</i>
 * is greater than <i>str2</i>, and 0 if they are
 * equal.
 */
#[Pure]
function strncmp(string $string1, string $string2, int $length): int {}

/**
 * Binary safe case-insensitive string comparison
 * @link https://php.net/manual/en/function.strcasecmp.php
 * @param string $string1 <p>
 * The first string
 * </p>
 * @param string $string2 <p>
 * The second string
 * </p>
 * @return int less than 0 if <i>str1</i> is less than
 * <i>str2</i>; &gt; 0 if <i>str1</i>
 * is greater than <i>str2</i>, and 0 if they are
 * equal.
 */
#[Pure]
function strcasecmp(string $string1, string $string2): int {}

/**
 * Binary safe case-insensitive string comparison of the first n characters
 * @link https://php.net/manual/en/function.strncasecmp.php
 * @param string $string1 <p>
 * The first string.
 * </p>
 * @param string $string2 <p>
 * The second string.
 * </p>
 * @param int $length <p>
 * The length of strings to be used in the comparison.
 * </p>
 * @return int less than 0 if <i>str1</i> is less than
 * <i>str2</i>; &gt; 0 if <i>str1</i> is
 * greater than <i>str2</i>, and 0 if they are equal.
 */
#[Pure]
function strncasecmp(string $string1, string $string2, int $length): int {}

/**
 * The function returns {@see true} if the passed $haystack starts from the
 * $needle string or {@see false} otherwise.
 *
 * @param string $haystack
 * @param string $needle
 * @return bool
 * @since 8.0
 */
#[Pure]
function str_starts_with(string $haystack, string $needle): bool {}

/**
 * The function returns {@see true} if the passed $haystack ends with the
 * $needle string or {@see false} otherwise.
 *
 * @param string $haystack
 * @param string $needle
 * @return bool
 * @since 8.0
 */
#[Pure]
function str_ends_with(string $haystack, string $needle): bool {}

/**
 * Checks if $needle is found in $haystack and returns a boolean value
 * (true/false) whether or not the $needle was found.
 *
 * @param string $haystack
 * @param string $needle
 * @return bool
 * @since 8.0
 */
#[Pure]
function str_contains(string $haystack, string $needle): bool {}

/**
 * Return the current key and value pair from an array and advance the array cursor
 * @link https://php.net/manual/en/function.each.php
 * @param array|ArrayObject &$array <p>
 * The input array.
 * </p>
 * @return array the current key and value pair from the array
 * <i>array</i>. This pair is returned in a four-element
 * array, with the keys 0, 1,
 * key, and value. Elements
 * 0 and key contain the key name of
 * the array element, and 1 and value
 * contain the data.
 * </p>
 * <p>
 * If the internal pointer for the array points past the end of the
 * array contents, <b>each</b> returns
 * false.
 * @removed 8.0
 */
#[Deprecated(reason: "Use a foreach loop instead", since: "7.2")]
function each(&$array): array {}

/**
 * Sets which PHP errors are reported
 * @link https://php.net/manual/en/function.error-reporting.php
 * @param int|null $error_level [optional] <p>
 * The new error_reporting
 * level. It takes on either a bitmask, or named constants. Using named
 * constants is strongly encouraged to ensure compatibility for future
 * versions. As error levels are added, the range of integers increases,
 * so older integer-based error levels will not always behave as expected.
 * </p>
 * <p>
 * The available error level constants and the actual
 * meanings of these error levels are described in the
 * predefined constants.
 * <table>
 * error_reporting level constants and bit values
 * <tr valign="top">
 * <td>value</td>
 * <td>constant</td>
 * </tr>
 * <tr valign="top">
 * <td>1</td>
 * <td>
 * E_ERROR
 * </td>
 * </tr>
 * <tr valign="top">
 * <td>2</td>
 * <td>
 * E_WARNING
 * </td>
 * </tr>
 * <tr valign="top">
 * <td>4</td>
 * <td>
 * E_PARSE
 * </td>
 * </tr>
 * <tr valign="top">
 * <td>8</td>
 * <td>
 * E_NOTICE
 * </td>
 * </tr>
 * <tr valign="top">
 * <td>16</td>
 * <td>
 * E_CORE_ERROR
 * </td>
 * </tr>
 * <tr valign="top">
 * <td>32</td>
 * <td>
 * E_CORE_WARNING
 * </td>
 * </tr>
 * <tr valign="top">
 * <td>64</td>
 * <td>
 * E_COMPILE_ERROR
 * </td>
 * </tr>
 * <tr valign="top">
 * <td>128</td>
 * <td>
 * E_COMPILE_WARNING
 * </td>
 * </tr>
 * <tr valign="top">
 * <td>256</td>
 * <td>
 * E_USER_ERROR
 * </td>
 * </tr>
 * <tr valign="top">
 * <td>512</td>
 * <td>
 * E_USER_WARNING
 * </td>
 * </tr>
 * <tr valign="top">
 * <td>1024</td>
 * <td>
 * E_USER_NOTICE
 * </td>
 * </tr>
 * <tr valign="top">
 * <td>32767</td>
 * <td>
 * E_ALL
 * </td>
 * </tr>
 * <tr valign="top">
 * <td>2048</td>
 * <td>
 * E_STRICT
 * </td>
 * </tr>
 * <tr valign="top">
 * <td>4096</td>
 * <td>
 * E_RECOVERABLE_ERROR
 * </td>
 * </tr>
 * <tr valign="top">
 * <td>8192</td>
 * <td>
 * E_DEPRECATED
 * </td>
 * </tr>
 * <tr valign="top">
 * <td>16384</td>
 * <td>
 * E_USER_DEPRECATED
 * </td>
 * </tr>
 * </table>
 * </p>
 * @return int the old error_reporting
 * level or the current level if no <i>level</i> parameter is
 * given.
 */
function error_reporting(?int $error_level): int {}

/**
 * Defines a named constant
 * @link https://php.net/manual/en/function.define.php
 * @param string $constant_name <p>
 * The name of the constant.
 * </p>
 * @param null|array|bool|int|float|string $value <p>
 * The value of the constant.
 * In PHP 5, value must be a scalar value (integer, float, string, boolean, or null).
 * In PHP 7, array values are also accepted.
 * It is possible to define resource constants,
 * however it is not recommended and may cause unpredictable behavior.
 * </p>
 * @param bool $case_insensitive <p>
 * If set to true, the constant will be defined case-insensitive.
 * The default behavior is case-sensitive; i.e.
 * CONSTANT and Constant represent
 * different values.
 * Defining case-insensitive constants is deprecated as of PHP 7.3.0.
 * </p>
 * <p>
 * Case-insensitive constants are stored as lower-case.
 * </p>
 * @return bool true on success or false on failure.
 */
function define(
    string $constant_name,
    #[LanguageLevelTypeAware(['8.1' => 'mixed'], default: 'null|array|bool|int|float|string')] $value,
    #[PhpStormStubsElementAvailable(from: '5.3', to: '5.6')] bool $case_insensitive,
    #[PhpStormStubsElementAvailable(from: '7.0')] #[Deprecated(since: 7.3)] bool $case_insensitive = false
): bool {}

/**
 * Checks whether a given named constant exists
 * @link https://php.net/manual/en/function.defined.php
 * @param string $constant_name <p>
 * The constant name.
 * </p>
 * @return bool true if the named constant given by <i>name</i>
 * has been defined, false otherwise.
 */
#[Pure(true)]
function defined(string $constant_name): bool {}

/**
 * Returns the name of the class of an object
 * @link https://php.net/manual/en/function.get-class.php
 * @param object $object [optional] <p>
 * The tested object. This parameter may be omitted when inside a class.
 * </p>
 * @return string <p>The name of the class of which <i>object</i> is an
 * instance.
 * If <i>object</i> is omitted when inside a class, the
 * name of that class is returned.</p>
 */
#[Pure]
function get_class(object $object): string {}

/**
 * the "Late Static Binding" class name
 * @link https://php.net/manual/en/function.get-called-class.php
 * @return string
 */
#[Pure]
function get_called_class(): string {}

/**
 * Retrieves the parent class name for object or class
 * @link https://php.net/manual/en/function.get-parent-class.php
 * @param object|string $object_or_class [optional] <p>
 * The tested object or class name
 * </p>
 * @return string|false <p>The name of the parent class of the class of which
 * <i>object</i> is an instance or the name.
 * </p>
 * <p>
 * If the object does not have a parent false will be returned.
 * </p>
 * <p>
 * If called without parameter outside object, this function returns false.</p>
 */
#[Pure]
function get_parent_class(object|string $object_or_class): string|false {}

/**
 * Checks if the class method exists
 * @link https://php.net/manual/en/function.method-exists.php
 * @param object|string $object_or_class <p>
 * An object instance or a class name
 * </p>
 * @param string $method <p>
 * The method name
 * </p>
 * @return bool true if the method given by <i>method_name</i>
 * has been defined for the given <i>object</i>, false
 * otherwise.
 */
#[Pure]
function method_exists($object_or_class, string $method): bool {}

/**
 * Checks if the object or class has a property
 * @link https://php.net/manual/en/function.property-exists.php
 * @param object|string $object_or_class <p>
 * The class name or an object of the class to test for
 * </p>
 * @param string $property <p>
 * The name of the property
 * </p>
 * @return bool true if the property exists, false if it doesn't exist or
 * null in case of an error.
 */
#[Pure]
function property_exists($object_or_class, string $property): bool {}

/**
 * Checks if the trait exists
 * @param string $trait Name of the trait to check
 * @param bool $autoload [optional] Whether to autoload if not already loaded.
 * @return bool Returns TRUE if trait exists, FALSE if not, NULL in case of an error.
 * @link https://secure.php.net/manual/en/function.trait-exists.php
 * @since 5.4
 */
function trait_exists(string $trait, bool $autoload = true): bool {}

/**
 * Checks if the class has been defined
 * @link https://php.net/manual/en/function.class-exists.php
 * @param string $class <p>
 * The class name. The name is matched in a case-insensitive manner.
 * </p>
 * @param bool $autoload [optional] <p>
 * Whether or not to call autoload by default.
 * </p>
 * @return bool true if <i>class_name</i> is a defined class,
 * false otherwise.
 */
function class_exists(string $class, bool $autoload = true): bool {}

/**
 * Checks if the interface has been defined
 * @link https://php.net/manual/en/function.interface-exists.php
 * @param string $interface <p>
 * The interface name
 * </p>
 * @param bool $autoload [optional] <p>
 * Whether to call autoload or not by default.
 * </p>
 * @return bool true if the interface given by
 * <i>interface_name</i> has been defined, false otherwise.
 * @since 5.0.2
 */
function interface_exists(string $interface, bool $autoload = true): bool {}

/**
 * Return true if the given function has been defined
 * @link https://php.net/manual/en/function.function-exists.php
 * @param string $function <p>
 * The function name, as a string.
 * </p>
 * @return bool true if <i>function_name</i> exists and is a
 * function, false otherwise.
 * </p>
 * <p>
 * This function will return false for constructs, such as
 * <b>include_once</b> and <b>echo</b>.
 */
#[Pure(true)]
function function_exists(string $function): bool {}

/**
 * Checks if the enum has been defined
 * @link https://php.net/manual/en/function.enum-exists.php
 * @param string $enum <p>
 * The enum name. The name is matched in a case-insensitive manner.
 * </p>
 * @param bool $autoload [optional] <p>
 * Whether or not to call autoload by default.
 * </p>
 * @return bool true if <i>enum</i> is a defined enum,
 * false otherwise.
 * @since 8.1
 */
function enum_exists(string $enum, bool $autoload = true): bool {}

/**
 * Creates an alias for a class
 * @link https://php.net/manual/en/function.class-alias.php
 * @param string $class The original class.
 * @param string $alias The alias name for the class.
 * @param bool $autoload [optional] Whether to autoload if the original class is not found.
 * @return bool true on success or false on failure.
 */
function class_alias(string $class, string $alias, bool $autoload = true): bool {}

/**
 * Returns an array with the names of included or required files
 * @link https://php.net/manual/en/function.get-included-files.php
 * @return string[] an array of the names of all files.
 * <p>
 * The script originally called is considered an "included file," so it will
 * be listed together with the files referenced by
 * <b>include</b> and family.
 * </p>
 * <p>
 * Files that are included or required multiple times only show up once in
 * the returned array.
 * </p>
 */
#[Pure(true)]
function get_included_files(): array {}

/**
 * Alias of <b>get_included_files</b>
 * @link https://php.net/manual/en/function.get-required-files.php
 * @return string[]
 */
#[Pure(true)]
function get_required_files(): array {}

/**
 * checks if the object has this class as one of its parents or implements it
 * @link https://php.net/manual/en/function.is-subclass-of.php
 * @param object|string $object_or_class <p>
 * A class name or an object instance
 * </p>
 * @param string $class <p>
 * The class name
 * </p>
 * @param bool $allow_string [optional] <p>
 * If this parameter set to false, string class name as object is not allowed.
 * This also prevents from calling autoloader if the class doesn't exist.
 * </p>
 * @return bool This function returns true if the object <i>object</i>,
 * belongs to a class which is a subclass of
 * <i>class_name</i>, false otherwise.
 */
#[Pure]
function is_subclass_of(mixed $object_or_class, string $class, bool $allow_string = true): bool {}

/**
 * Checks if the object is of this class or has this class as one of its parents
 * @link https://php.net/manual/en/function.is-a.php
 * @param object|string $object_or_class <p>
 * The tested object
 * </p>
 * @param string $class <p>
 * The class name
 * </p>
 * @param bool $allow_string [optional] <p>
 * If this parameter set to <b>FALSE</b>, string class name as <em><b>object</b></em>
 * is not allowed. This also prevents from calling autoloader if the class doesn't exist.
 * </p>
 * @return bool <b>TRUE</b> if the object is of this class or has this class as one of
 * its parents, <b>FALSE</b> otherwise.
 */
#[Pure]
function is_a(mixed $object_or_class, string $class, bool $allow_string = false): bool {}

/**
 * Get the default properties of the class
 * @link https://php.net/manual/en/function.get-class-vars.php
 * @param string $class <p>
 * The class name
 * </p>
 * @return array an associative array of declared properties visible from the
 * current scope, with their default value.
 * The resulting array elements are in the form of
 * varname => value.
 */
#[Pure]
function get_class_vars(string $class): array {}

/**
 * Gets the properties of the given object
 * @link https://php.net/manual/en/function.get-object-vars.php
 * @param object $object <p>
 * An object instance.
 * </p>
 * @return array an associative array of defined object accessible non-static properties
 * for the specified <i>object</i> in scope. If a property have
 * not been assigned a value, it will be returned with a null value.
 */
#[Pure]
function get_object_vars(object $object): array {}

/**
 * Gets the class methods' names
 * @link https://php.net/manual/en/function.get-class-methods.php
 * @param object|string $object_or_class <p>
 * The class name or an object instance
 * </p>
 * @return string[] an array of method names defined for the class specified by
 * <i>class_name</i>. In case of an error, it returns null.
 */
#[Pure]
function get_class_methods(object|string $object_or_class): array {}

/**
 * Generates a user-level error/warning/notice message
 * @link https://php.net/manual/en/function.trigger-error.php
 * @param string $message <p>
 * The designated error message for this error. It's limited to 1024
 * characters in length. Any additional characters beyond 1024 will be
 * truncated.
 * </p>
 * @param int $error_level [optional] <p>
 * The designated error type for this error. It only works with the E_USER
 * family of constants, and will default to <b>E_USER_NOTICE</b>.
 * </p>
 * @return bool This function returns false if wrong <i>error_type</i> is
 * specified, true otherwise.
 */
function trigger_error(string $message, int $error_level = E_USER_NOTICE): bool {}

/**
 * Alias of <b>trigger_error</b>
 * @link https://php.net/manual/en/function.user-error.php
 * @param string $message
 * @param int $error_level [optional]
 * @return bool This function returns false if wrong <i>error_type</i> is
 * specified, true otherwise.
 */
function user_error(string $message, int $error_level = E_USER_NOTICE): bool {}

/**
 * Sets a user-defined error handler function
 * @link https://php.net/manual/en/function.set-error-handler.php
 * @param callable|null $callback <p>
 * The user function needs to accept two parameters: the error code, and a
 * string describing the error. Then there are three optional parameters
 * that may be supplied: the filename in which the error occurred, the
 * line number in which the error occurred, and the context in which the
 * error occurred (an array that points to the active symbol table at the
 * point the error occurred). The function can be shown as:
 * </p>
 * <p>
 * <b>handler</b>
 * <b>int<i>errno</i></b>
 * <b>string<i>errstr</i></b>
 * <b>string<i>errfile</i></b>
 * <b>int<i>errline</i></b>
 * <b>array<i>errcontext</i></b>
 * <i>errno</i>
 * The first parameter, <i>errno</i>, contains the
 * level of the error raised, as an integer.</p>
 * @param int $error_levels [optional] <p>
 * Can be used to mask the triggering of the
 * <i>error_handler</i> function just like the error_reporting ini setting
 * controls which errors are shown. Without this mask set the
 * <i>error_handler</i> will be called for every error
 * regardless to the setting of the error_reporting setting.
 * </p>
 * @return callable|null a string containing the previously defined error handler (if any). If
 * the built-in error handler is used null is returned. null is also returned
 * in case of an error such as an invalid callback. If the previous error handler
 * was a class method, this function will return an indexed array with the class
 * and the method name.
 */
function set_error_handler(?callable $callback, int $error_levels = E_ALL|E_STRICT) {}

/**
 * Restores the previous error handler function
 * @link https://php.net/manual/en/function.restore-error-handler.php
 * @return bool This function always returns true.
 */
#[LanguageLevelTypeAware(['8.2' => 'true'], default: 'bool')]
function restore_error_handler(): bool {}

/**
 * Sets a user-defined exception handler function
 * @link https://php.net/manual/en/function.set-exception-handler.php
 * @param callable|null $callback <p>
 * Name of the function to be called when an uncaught exception occurs.
 * This function must be defined before calling
 * <b>set_exception_handler</b>. This handler function
 * needs to accept one parameter, which will be the exception object that
 * was thrown.
 * NULL may be passed instead, to reset this handler to its default state.
 * </p>
 * @return callable|null the name of the previously defined exception handler, or null on error. If
 * no previous handler was defined, null is also returned.
 */
function set_exception_handler(?callable $callback) {}

/**
 * Restores the previously defined exception handler function
 * @link https://php.net/manual/en/function.restore-exception-handler.php
 * @return bool This function always returns true.
 */
#[LanguageLevelTypeAware(['8.2' => 'true'], default: 'bool')]
function restore_exception_handler(): bool {}

/**
 * Returns an array with the name of the defined classes
 * @link https://php.net/manual/en/function.get-declared-classes.php
 * @return string[] an array of the names of the declared classes in the current script.
 * <p>
 * Note that depending on what extensions you have compiled or
 * loaded into PHP, additional classes could be present. This means that
 * you will not be able to define your own classes using these
 * names. There is a list of predefined classes in the Predefined Classes section of
 * the appendices.
 * </p>
 */
#[Pure(true)]
function get_declared_classes(): array {}

/**
 * Returns an array of all declared interfaces
 * @link https://php.net/manual/en/function.get-declared-interfaces.php
 * @return string[] an array of the names of the declared interfaces in the current
 * script.
 */
#[Pure(true)]
function get_declared_interfaces(): array {}

/**
 * Returns an array of all declared traits
 * @return array with names of all declared traits in values. Returns NULL in case of a failure.
 * @link https://secure.php.net/manual/en/function.get-declared-traits.php
 * @see class_uses()
 * @since 5.4
 */
#[Pure(true)]
function get_declared_traits(): array {}

/**
 * Returns an array of all defined functions
 * @link https://php.net/manual/en/function.get-defined-functions.php
 * @param bool $exclude_disabled [optional] Whether disabled functions should be excluded from the return value.
 * @return array an multidimensional array containing a list of all defined
 * functions, both built-in (internal) and user-defined. The internal
 * functions will be accessible via $arr["internal"], and
 * the user defined ones using $arr["user"] (see example
 * below).
 */
#[Pure(true)]
function get_defined_functions(#[PhpStormStubsElementAvailable(from: '7.1')] bool $exclude_disabled = true): array {}

/**
 * Returns an array of all defined variables
 * @link https://php.net/manual/en/function.get-defined-vars.php
 * @return array A multidimensional array with all the variables.
 */
#[Pure(true)]
function get_defined_vars(): array {}

/**
 * Create an anonymous (lambda-style) function
 * @link https://php.net/manual/en/function.create-function.php
 * @param string $args <p>
 * The function arguments.
 * </p>
 * @param string $code <p>
 * The function code.
 * </p>
 * @return string|false a unique function name as a string, or false on error.
 * @removed 8.0
 */
#[Deprecated(reason: "Use anonymous functions instead", since: "7.2")]
function create_function(string $args, string $code): false|string {}

/**
 * Returns the resource type
 * @link https://php.net/manual/en/function.get-resource-type.php
 * @param resource $resource <p>
 * The evaluated resource handle.
 * </p>
 * @return string If the given <i>handle</i> is a resource, this function
 * will return a string representing its type. If the type is not identified
 * by this function, the return value will be the string
 * Unknown.
 */
function get_resource_type($resource): string {}

/**
 * Returns an array with the names of all modules compiled and loaded
 * @link https://php.net/manual/en/function.get-loaded-extensions.php
 * @param bool $zend_extensions [optional] <p>
 * Only return Zend extensions, if not then regular extensions, like
 * mysqli are listed. Defaults to false (return regular extensions).
 * </p>
 * @return string[] an indexed array of all the modules names.
 */
#[Pure]
function get_loaded_extensions(bool $zend_extensions = false): array {}

/**
 * Find out whether an extension is loaded
 * @link https://php.net/manual/en/function.extension-loaded.php
 * @param string $extension <p>
 * The extension name.
 * </p>
 * <p>
 * You can see the names of various extensions by using
 * <b>phpinfo</b> or if you're using the
 * CGI or CLI version of
 * PHP you can use the -m switch to
 * list all available extensions:
 * <pre>
 * $ php -m
 * [PHP Modules]
 * xml
 * tokenizer
 * standard
 * sockets
 * session
 * posix
 * pcre
 * overload
 * mysql
 * mbstring
 * ctype
 * [Zend Modules]
 * </pre>
 * </p>
 * @return bool true if the extension identified by <i>name</i>
 * is loaded, false otherwise.
 */
#[Pure]
function extension_loaded(string $extension): bool {}

/**
 * Returns an array with the names of the functions of a module
 * @link https://php.net/manual/en/function.get-extension-funcs.php
 * @param string $extension <p>
 * The module name.
 * </p>
 * <p>
 * This parameter must be in lowercase.
 * </p>
 * @return string[]|false an array with all the functions, or false if
 * <i>module_name</i> is not a valid extension.
 */
#[Pure]
function get_extension_funcs(string $extension): array|false {}

/**
 * Returns an associative array with the names of all the constants and their values
 * @link https://php.net/manual/en/function.get-defined-constants.php
 * @param bool $categorize [optional] <p>
 * Causing this function to return a multi-dimensional
 * array with categories in the keys of the first dimension and constants
 * and their values in the second dimension.
 * <code>
 * define("MY_CONSTANT", 1);
 * print_r(get_defined_constants(true));
 * </code>
 * The above example will output something similar to:
 * <pre>
 * Array
 * (
 * [Core] => Array
 * (
 * [E_ERROR] => 1
 * [E_WARNING] => 2
 * [E_PARSE] => 4
 * [E_NOTICE] => 8
 * [E_CORE_ERROR] => 16
 * [E_CORE_WARNING] => 32
 * [E_COMPILE_ERROR] => 64
 * [E_COMPILE_WARNING] => 128
 * [E_USER_ERROR] => 256
 * [E_USER_WARNING] => 512
 * [E_USER_NOTICE] => 1024
 * [E_STRICT] => 2048
 * [E_RECOVERABLE_ERROR] => 4096
 * [E_DEPRECATED] => 8192
 * [E_USER_DEPRECATED] => 16384
 * [E_ALL] => 32767
 * [TRUE] => 1
 * )
 * [pcre] => Array
 * (
 * [PREG_PATTERN_ORDER] => 1
 * [PREG_SET_ORDER] => 2
 * [PREG_OFFSET_CAPTURE] => 256
 * [PREG_SPLIT_NO_EMPTY] => 1
 * [PREG_SPLIT_DELIM_CAPTURE] => 2
 * [PREG_SPLIT_OFFSET_CAPTURE] => 4
 * [PREG_GREP_INVERT] => 1
 * )
 * [user] => Array
 * (
 * [MY_CONSTANT] => 1
 * )
 * )
 * </pre>
 * </p>
 * @return array
 */
#[Pure(true)]
function get_defined_constants(bool $categorize = false): array {}

/**
 * Generates a backtrace
 * @link https://php.net/manual/en/function.debug-backtrace.php
 * @param int $options [optional] <p>
 * As of 5.3.6, this parameter is a bitmask for the following options:</p>
 * <b>debug_backtrace</b> options
 * <table>
 * <tr valign="top">
 * <td>DEBUG_BACKTRACE_PROVIDE_OBJECT</td>
 * <td>
 * Whether or not to populate the "object" index.
 * </td>
 * </tr>
 * <tr valign="top">
 * <td>DEBUG_BACKTRACE_IGNORE_ARGS</td>
 * <td>
 * Whether or not to omit the "args" index, and thus all the function/method arguments,
 * to save memory.
 * </td>
 * </tr>
 * </table>
 * <p>
 * Before 5.3.6, the only values recognized are true or false, which are the same as
 * setting or not setting the <b>DEBUG_BACKTRACE_PROVIDE_OBJECT</b> option respectively.
 * </p>
 * @param int $limit [optional] <p>
 * As of 5.4.0, this parameter can be used to limit the number of stack frames returned.
 * By default (<i>limit</i>=0) it returns all stack frames.
 * </p>
 * @return array <p>an array of associative arrays. The possible returned elements
 * are as follows:
 * </p>
 * <p>
 * Possible returned elements from <b>debug_backtrace</b>
 * </p>
 * <table>
 * <tr valign="top">
 * <td>Name</td>
 * <td>Type</td>
 * <td>Description</td>
 * </tr>
 * <tr valign="top">
 * <td>function</td>
 * <td>string</td>
 * <td>
 * The current function name. See also
 * __FUNCTION__.
 * </td>
 * </tr>
 * <tr valign="top">
 * <td>line</td>
 * <td>integer</td>
 * <td>
 * The current line number. See also
 * __LINE__.
 * </td>
 * </tr>
 * <tr valign="top">
 * <td>file</td>
 * <td>string</td>
 * <td>
 * The current file name. See also
 * __FILE__.
 * </td>
 * </tr>
 * <tr valign="top">
 * <td>class</td>
 * <td>string</td>
 * <td>
 * The current class name. See also
 * __CLASS__
 * </td>
 * </tr>
 * <tr valign="top">
 * <td>object</td>
 * <td>object</td>
 * <td>
 * The current object.
 * </td>
 * </tr>
 * <tr valign="top">
 * <td>type</td>
 * <td>string</td>
 * <td>
 * The current call type. If a method call, "->" is returned. If a static
 * method call, "::" is returned. If a function call, nothing is returned.
 * </td>
 * </tr>
 * <tr valign="top">
 * <td>args</td>
 * <td>array</td>
 * <td>
 * If inside a function, this lists the functions arguments. If
 * inside an included file, this lists the included file name(s).
 * </td>
 * </tr>
 * </table>
 */
function debug_backtrace(int $options = DEBUG_BACKTRACE_PROVIDE_OBJECT, int $limit = 0): array {}

/**
 * Prints a backtrace
 * @link https://php.net/manual/en/function.debug-print-backtrace.php
 * @param int $options [optional] <p>
 * As of 5.3.6, this parameter is a bitmask for the following options:
 * <b>debug_print_backtrace</b> options
 * <table>
 * <tr valign="top">
 * <td><b>DEBUG_BACKTRACE_IGNORE_ARGS</b></td>
 * <td>
 * Whether or not to omit the "args" index, and thus all the function/method arguments,
 * to save memory.
 * </td>
 * </tr>
 * </table>
 * </p>
 * @param int $limit [optional] <p>
 * As of 5.4.0, this parameter can be used to limit the number of stack frames printed.
 * By default (<i>limit</i> = 0) it prints all stack frames.
 * </p>
 * @return void
 */
function debug_print_backtrace(
    int $options = 0,
    #[PhpStormStubsElementAvailable(from: '7.0')] int $limit = 0
): void {}

/**
 * Forces collection of any existing garbage cycles
 * @link https://php.net/manual/en/function.gc-collect-cycles.php
 * @return int number of collected cycles.
 */
function gc_collect_cycles(): int {}

/**
 * Returns status of the circular reference collector
 * @link https://php.net/manual/en/function.gc-enabled.php
 * @return bool true if the garbage collector is enabled, false otherwise.
 */
#[Pure(true)]
function gc_enabled(): bool {}

/**
 * Activates the circular reference collector
 * @link https://php.net/manual/en/function.gc-enable.php
 * @return void
 */
function gc_enable(): void {}

/**
 * Deactivates the circular reference collector
 * @link https://php.net/manual/en/function.gc-disable.php
 * @return void
 */
function gc_disable(): void {}

/**
 * Gets information about the garbage collector
 * @link https://php.net/manual/en/function.gc-status.php
 * @return int[] associative array with the following elements:
 * <ul>
 * <li>"runs"</li>
 * <li>"collected"</li>
 * <li>"threshold"</li>
 * <li>"roots"</li>
 * </ul>
 * @since 7.3
 */
#[ArrayShape(["runs" => "int", "collected" => "int", "threshold" => "int", "roots" => "int"])]
#[Pure(true)]
function gc_status(): array {}

/**
 * Reclaims memory used by the Zend Engine memory manager
 * @link https://php.net/manual/en/function.gc-mem-caches.php
 * @return int Returns the number of bytes freed.
 * @since 7.0
 */
function gc_mem_caches(): int {}

/**
 * Returns active resources
 * @link https://php.net/manual/en/function.get-resources.php
 * @param string|null $type [optional]<p>
 *
 * If defined, this will cause get_resources() to only return resources of the given type. A list of resource types is available.
 *
 * If the string Unknown is provided as the type, then only resources that are of an unknown type will be returned.
 *
 * If omitted, all resources will be returned.
 * </p>
 * @return resource[] Returns an array of currently active resources, indexed by resource number.
 * @since 7.0
 */
#[Pure(true)]
function get_resources(?string $type): array {}
