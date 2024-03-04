<?php

// Start of sysvshm v.
use JetBrains\PhpStorm\Internal\LanguageLevelTypeAware;

/**
 * Creates or open a shared memory segment
 * @link https://php.net/manual/en/function.shm-attach.php
 * @param int $key <p>
 * A numeric shared memory segment ID
 * </p>
 * @param int|null $size [optional] <p>
 * The memory size. If not provided, default to the
 * sysvshm.init_mem in the <i>php.ini</i>, otherwise 10000
 * bytes.
 * </p>
 * @param int $permissions [optional] <p>
 * The optional permission bits. Default to 0666.
 * </p>
 * @return resource|SysvSharedMemory|false a shared memory segment identifier.
 */
#[LanguageLevelTypeAware(["8.0" => "SysvSharedMemory|false"], default: "resource|false")]
function shm_attach(int $key, ?int $size, int $permissions = 0666) {}

/**
 * Removes shared memory from Unix systems
 * @link https://php.net/manual/en/function.shm-remove.php
 * @param SysvSharedMemory $shm <p>
 * The shared memory identifier as returned by
 * <b>shm_attach</b>
 * </p>
 * @return bool <b>TRUE</b> on success or <b>FALSE</b> on failure.
 */
function shm_remove(#[LanguageLevelTypeAware(["8.0" => "SysvSharedMemory"], default: "resource")] $shm): bool {}

/**
 * Disconnects from shared memory segment
 * @link https://php.net/manual/en/function.shm-detach.php
 * @param SysvSharedMemory $shm <p>
 * A shared memory resource handle as returned by
 * <b>shm_attach</b>
 * </p>
 * @return bool <b>shm_detach</b> always returns <b>TRUE</b>.
 */
function shm_detach(#[LanguageLevelTypeAware(["8.0" => "SysvSharedMemory"], default: "resource")] $shm): bool {}

/**
 * Inserts or updates a variable in shared memory
 * @link https://php.net/manual/en/function.shm-put-var.php
 * @param SysvSharedMemory $shm <p>
 * A shared memory resource handle as returned by
 * <b>shm_attach</b>
 * </p>
 * @param int $key <p>
 * The variable key.
 * </p>
 * @param mixed $value <p>
 * The variable. All variable types
 * that <b>serialize</b> supports may be used: generally
 * this means all types except for resources and some internal objects
 * that cannot be serialized.
 * </p>
 * @return bool <b>TRUE</b> on success or <b>FALSE</b> on failure.
 */
function shm_put_var(#[LanguageLevelTypeAware(["8.0" => "SysvSharedMemory"], default: "resource")] $shm, int $key, mixed $value): bool {}

/**
 * Check whether a specific entry exists
 * @link https://php.net/manual/en/function.shm-has-var.php
 * @param SysvSharedMemory $shm <p>
 * Shared memory segment, obtained from <b>shm_attach</b>.
 * </p>
 * @param int $key <p>
 * The variable key.
 * </p>
 * @return bool <b>TRUE</b> if the entry exists, otherwise <b>FALSE</b>
 */
function shm_has_var(#[LanguageLevelTypeAware(["8.0" => "SysvSharedMemory"], default: "resource")] $shm, int $key): bool {}

/**
 * Returns a variable from shared memory
 * @link https://php.net/manual/en/function.shm-get-var.php
 * @param SysvSharedMemory $shm <p>
 * Shared memory segment, obtained from <b>shm_attach</b>.
 * </p>
 * @param int $key <p>
 * The variable key.
 * </p>
 * @return mixed the variable with the given key.
 */
function shm_get_var(#[LanguageLevelTypeAware(["8.0" => "SysvSharedMemory"], default: "resource")] $shm, int $key): mixed {}

/**
 * Removes a variable from shared memory
 * @link https://php.net/manual/en/function.shm-remove-var.php
 * @param SysvSharedMemory $shm <p>
 * The shared memory identifier as returned by
 * <b>shm_attach</b>
 * </p>
 * @param int $key <p>
 * The variable key.
 * </p>
 * @return bool <b>TRUE</b> on success or <b>FALSE</b> on failure.
 */
function shm_remove_var(#[LanguageLevelTypeAware(["8.0" => "SysvSharedMemory"], default: "resource")] $shm, int $key): bool {}

/**
 * @since 8.0
 */
final class SysvSharedMemory
{
    /**
     * Cannot directly construct SysvSharedMemory, use shm_attach() instead
     * @see shm_attach()
     */
    private function __construct() {}
}

// End of sysvshm v.
