<?php
declare(strict_types=1);

namespace SimpleKafkaClient;

class TopicPartition
{
    /**
     * TopicPartition constructor.
     * @param string $topicName
     * @param int $partition
     * @param int $offset
     */
    public function __construct(string $topicName, int $partition, int $offset = 0) {}

    /**
     * @return string|null
     * @throws Exception
     */
    public function getTopicName(): ?string {}

    /**
     * @param string $topicName
     * @return TopicPartition
     * @throws Exception
     */
    public function setTopicName(string $topicName): TopicPartition {}

    /**
     * @return int
     * @throws Exception
     */
    public function getPartition(): int {}

    /**
     * @param int $partition
     * @return TopicPartition
     */
    public function setPartition(int $partition): TopicPartition {}

    /**
     * @return int
     */
    public function getOffset(): int {}

    /**
     * @param int $offset
     * @return TopicPartition
     */
    public function setOffset(int $offset): TopicPartition {}
}
