<?php
declare(strict_types=1);

namespace SimpleKafkaClient\Metadata;

class Topic
{
    /**
     * @return string
     */
    public function getName(): string {}

    /**
     * @return int
     */
    public function getErrorCode(): int {}

    /**
     * @return Collection
     */
    public function getPartitions(): Collection {}
}
