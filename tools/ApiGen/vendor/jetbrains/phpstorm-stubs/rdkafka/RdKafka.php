<?php

use RdKafka\Exception;
use RdKafka\Metadata;
use RdKafka\Topic;
use RdKafka\TopicConf;
use RdKafka\TopicPartition;

abstract class RdKafka
{
    /**
     * @param string $broker_list
     *
     * @return int
     */
    public function addBrokers($broker_list) {}

    /**
     * @param bool  $all_topics
     * @param null|Topic $only_topic
     * @param int   $timeout_ms
     *
     * @throws Exception
     * @return Metadata
     */
    public function getMetadata($all_topics, $only_topic = null, $timeout_ms = 0) {}

    /**
     * @return int
     */
    public function getOutQLen() {}

    /**
     * @param string    $topic_name
     * @param null|TopicConf $topic_conf
     *
     * @return Topic
     */
    public function newTopic($topic_name, $topic_conf = null) {}

    /**
     * @param int $timeout_ms
     *
     * @return void
     */
    public function poll($timeout_ms) {}

    /**
     * @param int $level
     *
     * @return void
     */
    public function setLogLevel($level) {}

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
     * @param int $low
     * @param int $high
     * @param int $timeout_ms
     *
     * @return void
     */
    public function queryWatermarkOffsets($topic, $partition = 0, &$low = 0, &$high = 0, $timeout_ms = 0) {}

    /**
     * @param int $purge_flags
     *
     * @return int
     */
    public function purge($purge_flags) {}

    /**
     * @param int $timeout_ms
     *
     * @return int
     */
    public function flush($timeout_ms) {}

    public function metadata($all_topics, $only_topic = false, $timeout_ms = 0) {}

    public function setLogger($logger) {}

    public function outqLen() {}
}
