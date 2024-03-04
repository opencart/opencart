<?php

namespace RdKafka;

/**
 * Configuration reference: https://github.com/edenhill/librdkafka/blob/master/CONFIGURATION.md
 */
class TopicConf
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
     * @param int $partitioner
     *
     * @return void
     */
    public function setPartitioner($partitioner) {}
}
