<?php

declare(strict_types=1);

namespace Swoole\Atomic;

class Long
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
    public function cmpset(int $cmp_value, int $new_value) {}
}
