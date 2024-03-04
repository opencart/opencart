<?php
declare(strict_types=1);

namespace SimpleKafkaClient\Metadata;

class Broker
{
    /**
     * @return int
     */
    public function getId(): int {}

    /**
     * @return string
     */
    public function getHost(): string {}

    /**
     * @return int
     */
    public function getPort(): int {}
}
