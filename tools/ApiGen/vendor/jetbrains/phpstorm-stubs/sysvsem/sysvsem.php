<?php

// Start of sysvsem v.
use JetBrains\PhpStorm\Internal\LanguageLevelTypeAware;

/**
 * Get a semaphore id
 * @link https://php.net/manual/en/function.sem-get.php
 * @param int $key
 * @param int $max_acquire [optional] <p>
 * The number of processes that can acquire the semaphore simultaneously
 * is set to <i>max_acquire</i>.
 * </p>
 * @param int $permissions [optional] <p>
 * The semaphore permissions. Actually this value is
 * set only if the process finds it is the only process currently
 * attached to the semaphore.
 * </p>
 * @param bool $auto_release [optional] <p>
 * Specifies if the semaphore should be automatically released on request
 * shutdown.
 * </p>
 * @return resource|false|SysvSemaphore a positive semaphore identifier on success, or <b>FALSE</b> on
 * error.
 */
#[LanguageLevelTypeAware(["8.0" => "SysvSemaphore|false"], default: "resource|false")]
function sem_get(int $key, int $max_acquire = 1, int $permissions = 0666, bool $auto_release = true) {}

/**
 * Acquire a semaphore
 * @link https://php.net/manual/en/function.sem-acquire.php
 * @param SysvSemaphore|resource $semaphore <p>
 * <i>sem_identifier</i> is a semaphore resource,
 * obtained from <b>sem_get</b>.
 * </p>
 * @param bool $non_blocking [optional] <p>
 * Specifies if the process shouldn't wait for the semaphore to be acquired.
 * If set to <i>true</i>, the call will return <i>false</i> immediately if a
 * semaphore cannot be immediately acquired.
 * </p>
 * @return bool <b>TRUE</b> on success or <b>FALSE</b> on failure.
 */
function sem_acquire(#[LanguageLevelTypeAware(["8.0" => "SysvSemaphore"], default: "resource")] $semaphore, bool $non_blocking = false): bool {}

/**
 * Release a semaphore
 * @link https://php.net/manual/en/function.sem-release.php
 * @param SysvSemaphore|resource $semaphore <p>
 * A Semaphore resource handle as returned by
 * <b>sem_get</b>.
 * </p>
 * @return bool <b>TRUE</b> on success or <b>FALSE</b> on failure.
 */
function sem_release(#[LanguageLevelTypeAware(["8.0" => "SysvSemaphore"], default: "resource")] $semaphore): bool {}

/**
 * Remove a semaphore
 * @link https://php.net/manual/en/function.sem-remove.php
 * @param SysvSemaphore|resource $semaphore <p>
 * A semaphore resource identifier as returned
 * by <b>sem_get</b>.
 * </p>
 * @return bool <b>TRUE</b> on success or <b>FALSE</b> on failure.
 */
function sem_remove(#[LanguageLevelTypeAware(["8.0" => "SysvSemaphore"], default: "resource")] $semaphore): bool {}

/**
 * @since 8.0
 */
final class SysvSemaphore
{
    /**
     * Cannot directly construct SysvSemaphore, use sem_get() instead
     * @see sem_get()
     */
    private function __construct() {}
}

// End of sysvsem v.
