<?php
declare(strict_types=1);

namespace SimpleKafkaClient;

use SimpleKafkaClient;

class Consumer extends SimpleKafkaClient
{
    /**
     * Consumer constructor.
     * @param Configuration $configuration
     * @throws Exception
     */
    public function __construct(Configuration $configuration) {}

    /**
     * @param TopicPartition[]|null $topicPartitions
     * @throws Exception
     */
    public function assign(?array $topicPartitions): void {}

    /**
     * @return TopicPartition[]
     * @throws Exception
     */
    public function getAssignment(): array {}

    /**
     * @param string[] $topics
     * @throws Exception
     */
    public function subscribe(array $topics): void {}

    /**
     * @return string[]
     * @throws Exception
     */
    public function getSubscription(): array {}

    /**
     * @return void
     * @throws Exception
     */
    public function unsubscribe(): void {}

    /**
     * @param int $timeoutMs
     * @return Message
     */
    public function consume(int $timeoutMs): Message {}

    /**
     * @param Message|TopicPartition[] $messageOrOffsets
     * @throws Exception
     */
    public function commit($messageOrOffsets): void {}

    /**
     * @param Message|TopicPartition[] $messageOrOffsets
     * @throws Exception
     */
    public function commitAsync($messageOrOffsets): void {}

    /**
     * @return void
     */
    public function close(): void {}

    /**
     * @param TopicPartition[] $topicPartitions
     * @param int $timeoutMs
     * @return TopicPartition[]
     * @throws Exception
     */
    public function getCommittedOffsets(array $topicPartitions, int $timeoutMs): array {}

    /**
     * @param TopicPartition[] $topicPartitions
     * @return TopicPartition[]
     * @throws Exception
     */
    public function getOffsetPositions(array $topicPartitions): array {}
}
