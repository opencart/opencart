<?php

// Start of uopz v5.0.2

/**
 * Mark function as public, the default
 * @link https://secure.php.net/manual/en/uopz.constants.php#constant.zend-acc-public
 */
define('ZEND_ACC_PUBLIC', 1);

/**
 * Mark function as protected
 * @link https://secure.php.net/manual/en/uopz.constants.php#constant.zend-acc-protected
 */
define('ZEND_ACC_PROTECTED', 2);

/**
 * Mark function as private
 * @link https://secure.php.net/manual/en/uopz.constants.php#constant.zend-acc-private
 */
define('ZEND_ACC_PRIVATE', 4);

/**
 * Mark function as static
 * @link https://secure.php.net/manual/en/uopz.constants.php#constant.zend-acc-static
 */
define('ZEND_ACC_STATIC', 16);

/**
 * Mark function as final
 * @link https://secure.php.net/manual/en/uopz.constants.php#constant.zend-acc-final
 */
define('ZEND_ACC_FINAL', 32);

/**
 * Mark function as abstract
 * @link https://secure.php.net/manual/en/uopz.constants.php#constant.zend-acc-abstract
 */
define('ZEND_ACC_ABSTRACT', 64);

/**
 * Used for getting flags only
 * @link https://secure.php.net/manual/en/uopz.constants.php#constant.zend-acc-fetch
 */
define('ZEND_ACC_FETCH', PHP_INT_MAX);

/**
 * The bitmask of ZEND_ACC_PUBLIC | ZEND_ACC_PROTECTED | ZEND_ACC_PRIVATE
 */
define('ZEND_ACC_PPP_MASK', ZEND_ACC_PUBLIC|ZEND_ACC_PROTECTED|ZEND_ACC_PRIVATE);

/**
 * Adds non-existent method
 * @link  https://secure.php.net/manual/en/function.uopz-add-function.php
 * @param string $class The name of the class
 * @param string $function The name of the method
 * @param Closure $handler The Closure that defines the new method
 * @param int $flags Flags to set for the new method
 * @param bool $all Whether all classes that descend from class will also be affected
 * @return bool TRUE on success or FALSE on failure
 * @throws RuntimeException if the method to add already exists
 */
function uopz_add_function(...$arguments): bool {}

/**
 * Allows control over disabled exit opcode
 * @link https://secure.php.net/manual/en/function.uopz-allow-exit.php
 * @param bool $arguments Whether to allow the execution of exit opcodes or not.
 * @return void
 * @since 5.4
 */
function uopz_allow_exit(...$arguments): void {}

/**
 * Deletes previously added method
 * @link https://secure.php.net/manual/en/function.uopz-del-function.php
 * @param string $class The name of the class
 * @param string $function The name of the method
 * @param bool $all Whether all classes that descend from class will also be affected
 * @return bool TRUE on success or FALSE on failure
 * @throws RuntimeException if the method to delete has not been added by uopz_add_function()
 */
function uopz_del_function(...$arguments): bool {}

/**
 * Extend a class at runtime
 * @link https://secure.php.net/manual/en/function.uopz-extend.php
 * @param string $class The name of the class to extend
 * @param string $parent The name of the class to inherit
 * @return bool TRUE on success or FALSE on failure
 * @since 5.4
 */
function uopz_extend(...$arguments): bool {}

/**
 * Get or set flags on function or class
 * @link https://secure.php.net/manual/en/function.uopz-flags.php
 * @param string $class The name of a class
 * @param string $function The name of the function
 * @param int $flags A valid set of ZEND_ACC_ flags, ZEND_ACC_FETCH to read flags
 * @return int If setting, returns old flags, else returns flags
 * @since 5.4
 */
function uopz_flags(...$arguments): int {}

/**
 * Retrieve the last set exit status
 * @link https://secure.php.net/manual/en/function.uopz-get-exit-status.php
 * @return int|null The last exit status, or NULL if exit() has not been called
 * @since 5.4
 */
function uopz_get_exit_status(): ?int {}

/**
 * Gets previously set hook on method
 * @link https://secure.php.net/manual/en/function.uopz-get-hook.php
 * @param string $class The name of the class
 * @param string $function The name of the method
 * @return Closure|null The previously set hook, or NULL if no hook has been set
 */
function uopz_get_hook(...$arguments): ?Closure {}

/**
 * Get the current mock for a class
 * @link https://secure.php.net/manual/en/function.uopz-get-mock.php
 * @param string $arguments The name of the mocked class
 * @return string|object|null Either a string containing the name of the mock, or an object, or NULL if no mock has been set
 * @since 5.4
 */
function uopz_get_mock(...$arguments) {}

/**
 * Gets the value of a static class property, if class is given, or the value of an instance property, if instance is given
 * @link https://secure.php.net/manual/en/function.uopz-get-property.php
 * @param string|object $class The name of the class or the object instance
 * @param string $property The name of the property
 * @return mixed The value of the class or instance property, or NULL if the property is not defined
 */
function uopz_get_property(...$arguments) {}

/**
 * Gets a previous set return value for a function
 * @link https://secure.php.net/manual/en/function.uopz-get-return.php
 * @param string $class The name of the class containing the function
 * @param string $function The name of the function
 * @return mixed
 * @since 5.4
 */
function uopz_get_return(...$arguments) {}

/**
 * Gets the static variables from function or method scope
 * @link https://secure.php.net/manual/en/function.uopz-get-static.php
 * @param string $class The name of the class
 * @param string $function The name of the method
 * @return array|null An associative array of variable names mapped to their current values on success, or NULL if the method does not exist
 */
function uopz_get_static(...$arguments): ?array {}

/**
 * Implements an interface at runtime
 * @link https://secure.php.net/manual/en/function.uopz-implement.php
 * @param string $class The name of the class
 * @param string $interface The name of the interface
 * @return bool
 * @since 5.4
 */
function uopz_implement(...$arguments): bool {}

/**
 * Redefine a constant
 * @link https://secure.php.net/manual/en/function.uopz-redefine.php
 * @param string $class The name of the class containing the constant
 * @param string $constant The name of the constant
 * @param mixed $value The new value for the constant, must be a valid type for a constant variable
 * @return bool
 * @since 5.4
 */
function uopz_redefine(...$arguments): bool {}

/**
 * Sets hook to execute when entering a function or method
 * @link https://secure.php.net/manual/en/function.uopz-set-hook.php
 * @param string $class The name of the class
 * @param string $function The name of the method
 * @param Closure $hook A closure to execute when entering the method
 * @return bool TRUE on success or FALSE on failure
 */
function uopz_set_hook(...$arguments): bool {}

/**
 * Use mock instead of class for new objects
 * @link https://secure.php.net/manual/en/function.uopz-set-mock.php
 * @param string $class The name of the class to be mocked
 * @param string|object $mock The mock to use in the form of a string containing the name of the class to use or an object
 * @return void
 * @since 7.0
 */
function uopz_set_mock(...$arguments): void {}

/**
 * Sets the value of an existing static class property, if class is given, or the value of an existing instance property, if instance is given
 * @link https://secure.php.net/manual/en/function.uopz-set-property.php
 * @param string|object $class The name of the class or the object instance
 * @param string $property The name of the property
 * @param mixed $value The value to assign to the property
 * @return void
 */
function uopz_set_property(...$arguments): void {}

/**
 * Provide a return value for an existing function
 * @link https://secure.php.net/manual/en/function.uopz-set-return.php
 * @param string $class The name of the class containing the function
 * @param string $function The name of an existing function
 * @param mixed $value The value the function should return. If a Closure is provided and the execute flag is set, the Closure will be executed in place of the original function
 * @param bool $execute If true, and a Closure was provided as the value, the Closure will be executed in place of the original function.
 * @return bool
 * @since 7.0
 */
function uopz_set_return(...$arguments): bool {}

/**
 * Sets the static variables in function or method scope
 * @link https://secure.php.net/manual/en/function.uopz-set-static.php
 * @param string $class The name of the class
 * @param string $function The name of the method
 * @param array $static The associative array of variable names mapped to their values
 * @return void
 */
function uopz_set_static(...$arguments): void {}

/**
 * Undefine a constant
 * @link https://secure.php.net/manual/en/function.uopz-undefine.php
 * @param string $class The name of the class containing the constant
 * @param string $constant The name of the constant
 * @return bool
 * @since 5.4
 */
function uopz_undefine(...$arguments): bool {}

/**
 * Removes previously set hook on function or method
 * @link https://secure.php.net/manual/en/function.uopz-unset-hook.php
 * @param string $class The name of the class
 * @param string $function The name of the method
 * @return bool TRUE on success or FALSE on failure
 */
function uopz_unset_hook(...$arguments): bool {}

/**
 * Unset previously set mock
 * @link https://secure.php.net/manual/en/function.uopz-unset-mock.php
 * @param string $arguments The name of the mocked class
 * @return void
 * @since 7.0
 */
function uopz_unset_mock(...$arguments): void {}

/**
 * Unsets a previously set return value for a function
 * @link https://secure.php.net/manual/en/function.uopz-unset-return.php
 * @param string $class The name of the class containing the function
 * @param string $function The name of an existing function
 * @return bool
 * @since 7.0
 */
function uopz_unset_return(...$arguments): bool {}

function uopz_call_user_func(...$arguments) {}

function uopz_call_user_func_array(...$arguments) {}

// End of uopz v5.0.2
