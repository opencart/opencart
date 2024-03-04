<?php

declare(strict_types=1);

namespace Swoole;

class Runtime
{
    /**
     * To enable/disable runtime hooks in coroutines.
     *
     * For backward-compatible reason, there are four different ways to call this method:
     *   #1. Swoole\Runtime::enableCoroutine();             // Enable runtime hooks represented by constant SWOOLE_HOOK_ALL.
     *   #2. Swoole\Runtime::enableCoroutine($flags);       // Enable specified runtime hooks.
     *   #3. Swoole\Runtime::enableCoroutine(true, $flags); // Enable specified runtime hooks.
     *   #4. Swoole\Runtime::enableCoroutine(false);        // Disable runtime hooks.
     * Following statements are of the same (when used to disable runtime hooks):
     *   Swoole\Runtime::enableCoroutine(0);       // #2
     *   Swoole\Runtime::enableCoroutine(true, 0); // #3
     *   Swoole\Runtime::enableCoroutine(false);   // #4
     *
     * @param int|bool $enable
     * @return bool TRUE on success, or FALSE on failure.
     */
    public static function enableCoroutine($enable = true, int $flags = SWOOLE_HOOK_ALL) {}

    /**
     * @return int
     */
    public static function getHookFlags() {}

    /**
     * @return bool true on success or false on failure
     */
    public static function setHookFlags(int $flags) {}
}
