<?php
declare(strict_types=1);

namespace SimpleKafkaClient;

abstract class Topic
{
    /**
     * @return string
     */
    public function getName(): string {}
}

class ConsumerTopic extends Topic
{
    private function __construct() {}
}

class ProducerTopic extends Topic
{
    private function __construct() {}

    /**
     * @param int $partition
     * @param int $msgFlags
     * @param string|null $payload
     * @param string|null $key
     * @throws Exception
     */
    public function produce(int $partition, int $msgFlags, ?string $payload = null, ?string $key = null): void {}

    /**
     * @param int $partition
     * @param int $msgFlags
     * @param string|null $payload
     * @param string|null $key
     * @param array<string, mixed>|null $headers
     * @param int|null $timestampMs
     * @throws Exception
     */
    public function producev(int $partition, int $msgFlags, ?string $payload = null, ?string $key = null, ?array $headers = null, ?int $timestampMs = null): void {}
}
