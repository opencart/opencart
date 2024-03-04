<?php
declare(strict_types=1);

namespace SimpleKafkaClient;

use SimpleKafkaClient;

class Producer extends SimpleKafkaClient
{
    /**
     * Producer constructor.
     * @param Configuration $configuration
     * @throws Exception
     */
    public function __construct(Configuration $configuration) {}

    /**
     * @param int $timeoutMs
     * @throws Exception
     */
    public function initTransactions(int $timeoutMs): void {}

    /**
     * @return void
     * @throws Exception
     */
    public function beginTransaction(): void {}

    /**
     * @param int $timeoutMs
     * @throws Exception
     */
    public function commitTransaction(int $timeoutMs): void {}

    /**
     * @param int $timeoutMs
     * @throws Exception
     */
    public function abortTransaction(int $timeoutMs): void {}

    /**
     * @param int $timeoutMs
     * @return int
     */
    public function flush(int $timeoutMs): int {}

    /**
     * @param int $purgeFlags
     * @return int
     */
    public function purge(int $purgeFlags): int {}

    /**
     * @param string $topic
     * @return ProducerTopic
     */
    public function getTopicHandle(string $topic): ProducerTopic {}
}
