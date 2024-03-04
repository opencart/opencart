<?php

namespace RdKafka;

class TopicPartition
{
    /**
     * @param string $topic
     * @param int    $partition
     * @param int    $offset
     */
    public function __construct($topic, $partition, $offset = null) {}

    /**
     * @return int
     */
    public function getOffset() {}

    /**
     * @return int
     */
    public function getPartition() {}

    /**
     * @return string
     */
    public function getTopic() {}

    /**
     * @param int $offset
     *
     * @return void
     */
    public function setOffset($offset) {}

    /**
     * @param int $partition
     *
     * @return void
     */
    public function setPartition($partition) {}

    /**
     * @param string $topic_name
     *
     * @return void
     */
    public function setTopic($topic_name) {}
}
