<?php

// Start of SPL v.0.2
use JetBrains\PhpStorm\Internal\LanguageLevelTypeAware;
use JetBrains\PhpStorm\Pure;

/**
 * Return available SPL classes
 * @link https://php.net/manual/en/function.spl-classes.php
 * @return array
 */
#[Pure]
function spl_classes(): array {}

/**
 * Default implementation for __autoload()
 * @link https://php.net/manual/en/function.spl-autoload.php
 * @param string $class <p>
 * </p>
 * @param string|null $file_extensions [optional] <p>
 * By default it checks all include paths to
 * contain filenames built up by the lowercase class name appended by the
 * filename extensions .inc and .php.
 * </p>
 * @return void
 * @since 5.1.2
 */
function spl_autoload(string $class, ?string $file_extensions): void {}

/**
 * Register and return default file extensions for spl_autoload
 * @link https://php.net/manual/en/function.spl-autoload-extensions.php
 * @param string|null $file_extensions [optional] <p>
 * When calling without an argument, it simply returns the current list
 * of extensions each separated by comma. To modify the list of file
 * extensions, simply invoke the functions with the new list of file
 * extensions to use in a single string with each extensions separated
 * by comma.
 * </p>
 * @return string A comma delimited list of default file extensions for
 * spl_autoload.
 * @since 5.1.2
 */
function spl_autoload_extensions(?string $file_extensions): string {}

/**
 * Register given function as __autoload() implementation
 * @link https://php.net/manual/en/function.spl-autoload-register.php
 * @param callable|null $callback [optional] <p>
 * The autoload function being registered.
 * If no parameter is provided, then the default implementation of
 * spl_autoload will be registered.
 * </p>
 * @param bool $throw This parameter specifies whether spl_autoload_register() should throw exceptions when the
 * autoload_function cannot be registered. Ignored since since 8.0.
 * @param bool $prepend If true, spl_autoload_register() will prepend the autoloader on the autoload stack instead of
 * appending it.
 * @return bool true on success or false on failure.
 * @throws TypeError Since 8.0.
 * @since 5.1.2
 */
function spl_autoload_register(?callable $callback, bool $throw = true, bool $prepend = false): bool {}

/**
 * Unregister given function as __autoload() implementation
 * @link https://php.net/manual/en/function.spl-autoload-unregister.php
 * @param callable $callback <p>
 * The autoload function being unregistered.
 * </p>
 * @return bool true on success or false on failure.
 * @since 5.1.2
 */
function spl_autoload_unregister(callable $callback): bool {}

/**
 * Return all registered __autoload() functions
 * @link https://php.net/manual/en/function.spl-autoload-functions.php
 * @return array|false An array of all registered __autoload functions.
 * If the autoload stack is not activated then the return value is false.
 * If no function is registered the return value will be an empty array.
 * @since 5.1.2
 */
#[LanguageLevelTypeAware(["8.0" => "array"], default: "array|false")]
function spl_autoload_functions() {}

/**
 * Try all registered __autoload() functions to load the requested class
 * @link https://php.net/manual/en/function.spl-autoload-call.php
 * @param string $class <p>
 * The class name being searched.
 * </p>
 * @return void
 * @since 5.1.2
 */
function spl_autoload_call(string $class): void {}

/**
 * Return the parent classes of the given class
 * @link https://php.net/manual/en/function.class-parents.php
 * @param object|string $object_or_class <p>
 * An object (class instance) or a string (class name).
 * </p>
 * @param bool $autoload [optional] <p>
 * Whether to allow this function to load the class automatically through
 * the __autoload magic
 * method.
 * </p>
 * @return string[]|false An array on success, or false on error.
 */
#[Pure]
function class_parents($object_or_class, bool $autoload = true): array|false {}

/**
 * Return the interfaces which are implemented by the given class
 * @link https://php.net/manual/en/function.class-implements.php
 * @param object|string $object_or_class <p>
 * An object (class instance) or a string (class name).
 * </p>
 * @param bool $autoload [optional] <p>
 * Whether to allow this function to load the class automatically through
 * the __autoload magic
 * method.
 * </p>
 * @return string[]|false An array on success, or false on error.
 */
#[Pure]
function class_implements($object_or_class, bool $autoload = true): array|false {}

/**
 * Return hash id for given object
 * @link https://php.net/manual/en/function.spl-object-hash.php
 * @param object $object
 * @return string A string that is unique for each object and is always the same for
 * the same object.
 */
#[Pure]
function spl_object_hash(object $object): string {}

/**
 * Copy the iterator into an array
 * @link https://php.net/manual/en/function.iterator-to-array.php
 * @param Traversable $iterator <p>
 * The iterator being copied.
 * </p>
 * @param bool $preserve_keys [optional] <p>
 * Whether to use the iterator element keys as index.
 * </p>
 * @return array An array containing the elements of the iterator.
 */
function iterator_to_array(#[LanguageLevelTypeAware(['8.2' => 'Traversable|array'], default: 'Traversable')] $iterator, bool $preserve_keys = true): array {}

/**
 * Count the elements in an iterator
 * @link https://php.net/manual/en/function.iterator-count.php
 * @param Traversable $iterator <p>
 * The iterator being counted.
 * </p>
 * @return int The number of elements in iterator.
 */
#[Pure]
function iterator_count(#[LanguageLevelTypeAware(['8.2' => 'Traversable|array'], default: 'Traversable')] $iterator): int {}

/**
 * Call a function for every element in an iterator
 * @link https://php.net/manual/en/function.iterator-apply.php
 * @param Traversable $iterator <p>
 * The class to iterate over.
 * </p>
 * @param callable $callback <p>
 * The callback function to call on every element.
 * The function must return true in order to
 * continue iterating over the iterator.
 * </p>
 * @param array|null $args [optional] <p>
 * Arguments to pass to the callback function.
 * </p>
 * @return int the iteration count.
 */
function iterator_apply(Traversable $iterator, callable $callback, ?array $args): int {}

// End of SPL v.0.2

/**
 * Return the traits used by the given class
 * @param object|string $object_or_class An object (class instance) or a string (class name).
 * @param bool $autoload Whether to allow this function to load the class automatically through the __autoload() magic method.
 * @return string[]|false An array on success, or false on error.
 * @link https://php.net/manual/en/function.class-uses.php
 * @see class_parents()
 * @see get_declared_traits()
 * @since 5.4
 */
function class_uses($object_or_class, bool $autoload = true): array|false {}

/**
 * return the integer object handle for given object
 * @param object $object
 * @return int
 * @since 7.2
 */
function spl_object_id(object $object): int {}
