<?php

namespace RdKafka;

class Consumer extends \RdKafka
{
    /**
     * @param null|Conf $conf
     */
    public function __construct($conf = null) {}

    /**
     * @param string    $topic_name
     * @param null|TopicConf $topic_conf
     *
     * @return ConsumerTopic
     */
    public function newTopic($topic_name, ?TopicConf $topic_conf = null) {}

    /**
     * @return Queue
     */
    public function newQueue() {}
}
