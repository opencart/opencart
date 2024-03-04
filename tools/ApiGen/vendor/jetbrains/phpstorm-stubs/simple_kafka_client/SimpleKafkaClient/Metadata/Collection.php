<?php
declare(strict_types=1);

namespace SimpleKafkaClient\Metadata;

class Collection
{
    /**
     * @return int
     */
    public function count(): int {}

    /**
     * @return void
     */
    public function rewind(): void {}

    /**
     * @return mixed
     */
    public function current() {}

    /**
     * @return int
     */
    public function key(): int {}

    /**
     * @return mixed
     */
    public function next() {}

    /**
     * @return bool
     */
    public function valid(): bool {}
}
