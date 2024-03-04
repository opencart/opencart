<?php
declare(strict_types=1);

namespace SimpleKafkaClient\Metadata;

class Partition
{
    /**
     * @return int
     */
    public function getId(): int {}

    /**
     * @return int
     */
    public function getErrorCode(): int {}

    /**
     * @return int
     */
    public function getLeader(): int {}

    /**
     * @return Collection
     */
    public function getReplicas(): Collection {}

    /**
     * @return Collection
     */
    public function getIsrs(): Collection {}
}
