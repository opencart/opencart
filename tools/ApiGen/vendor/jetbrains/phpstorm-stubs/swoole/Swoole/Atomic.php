<?php

declare(strict_types=1);

namespace Swoole;

class Atomic
{
    public function __construct(int $value = 0) {}

    /**
     * @return int
     */
    public function add(int $add_value = 1) {}

    /**
     * @return int
     */
    public function sub(int $sub_value = 1) {}

    /**
     * @return int
     */
    public function get() {}

    public function set(int $value) {}

    /**
     * @return bool
     */
    public function wait(float $timeout = 1.0) {}

    /**
     * @return bool
     */
    public function wakeup(int $count = 1) {}

    /**
     * @return bool
     */
    public function cmpset(int $cmp_value, int $new_value) {}
}
