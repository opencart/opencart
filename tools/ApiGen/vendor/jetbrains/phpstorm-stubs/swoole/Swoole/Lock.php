<?php

declare(strict_types=1);

namespace Swoole;

class Lock
{
    public const FILELOCK = 2;
    public const MUTEX = 3;
    public const SEM = 4;
    public const RWLOCK = 1;
    public const SPINLOCK = 5;
    public $errCode = 0;

    public function __construct(int $type = self::MUTEX, string $filename = '') {}

    /**
     * @return bool
     */
    public function lock() {}

    /**
     * @return bool
     */
    public function lockwait(float $timeout = 1.0) {}

    /**
     * @return bool
     */
    public function trylock() {}

    /**
     * @return bool
     */
    public function lock_read() {}

    /**
     * @return bool
     */
    public function trylock_read() {}

    /**
     * @return bool
     */
    public function unlock() {}

    public function destroy() {}
}
