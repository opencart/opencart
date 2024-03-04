<?php

namespace RdKafka;

class KafkaConsumer
{
    /**
     * @param Conf $conf
     */
    public function __construct($conf) {}

    /**
     * @param TopicPartition[] $topic_partitions
     *
     * @throws Exception
     * @return void
     */
    public function assign($topic_partitions = null) {}

    /**
     * @param null|Message|TopicPartition[] $message_or_offsets
     *
     * @throws Exception
     * @return void
     */
    public function commit($message_or_offsets = null) {}

    /**
     * @param null|Message|TopicPartition[] $message_or_offsets
     *
     * @throws Exception
     * @return void
     */
    public function commitAsync($message_or_offsets = null) {}

    /**
     * @param int $timeout_ms
     *
     * @throws Exception
     * @throws \InvalidArgumentException
     * @return Message
     */
    public function consume($timeout_ms) {}

    /**
     * @throws Exception
     * @return TopicPartition[]
     */
    public function getAssignment() {}

    /**
     * @param bool               $all_topics
     * @param null|KafkaConsumerTopic $only_topic
     * @param int                $timeout_ms
     *
     * @throws Exception
     * @return Metadata
     */
    public function getMetadata($all_topics, $only_topic = null, $timeout_ms) {}

    /**
     * @return array
     */
    public function getSubscription() {}

    /**
     * @param array $topics
     *
     * @throws Exception
     * @return void
     */
    public function subscribe($topics) {}

    /**
     * @throws Exception
     * @return void
     */
    public function unsubscribe() {}

    /**
     * @param array $topic_partitions
     * @param int   $timeout_ms
     *
     * @return array
     */
    public function getCommittedOffsets($topic_partitions, $timeout_ms) {}

    /**
     * @param TopicPartition[] $topic_partitions
     * @param int $timeout_ms
     *
     * @return TopicPartition[]
     */
    public function offsetsForTimes($topic_partitions, $timeout_ms) {}

    /**
     * @param string $topic
     * @param int $partition
     * @param int &$low
     * @param int &$high
     * @param int $timeout_ms
     *
     * @return void
     */
    public function queryWatermarkOffsets($topic, $partition = 0, &$low = 0, &$high = 0, $timeout_ms = 0) {}

    /**
     * @param TopicPartition[] $topic_partitions
     */
    public function getOffsetPositions($topic_partitions) {}

    /**
     * @param string    $topic_name
     * @param null|TopicConf $topic_conf
     *
     * @return Topic
     */
    public function newTopic($topic_name, $topic_conf = null) {}

    /**
     * @return void
     */
    public function close() {}
}
