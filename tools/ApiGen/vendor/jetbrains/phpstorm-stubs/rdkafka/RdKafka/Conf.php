<?php

namespace RdKafka;

/**
 * Configuration reference: https://github.com/edenhill/librdkafka/blob/master/CONFIGURATION.md
 */
class Conf
{
    public function __construct() {}

    /**
     * @return array
     */
    public function dump() {}

    /**
     * @param string $name
     * @param string $value
     *
     * @return void
     */
    public function set($name, $value) {}

    /**
     * @param TopicConf $topic_conf
     *
     * @return void
     */
    public function setDefaultTopicConf($topic_conf) {}

    /**
     * @param callable $callback
     *
     * @return void
     */
    public function setDrMsgCb($callback) {}

    /**
     * @param callable $callback
     *
     * @return void
     */
    public function setErrorCb($callback) {}

    /**
     * @param callable $callback
     *
     * @return void
     */
    public function setRebalanceCb($callback) {}

    /**
     * @param callable $callback
     *
     * @return void
     */
    public function setStatsCb($callback) {}

    /**
     * @param callable $callback
     *
     * @return void
     */
    public function setOffsetCommitCb($callback) {}

    /**
     * @param callable $callback
     *
     * @return void
     */
    public function setConsumeCb($callback) {}

    /**
     * @param callable $callback
     *
     * @return void
     */
    public function setLogCb($callback) {}
}
