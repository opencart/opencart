<?php

namespace RdKafka;

class ProducerTopic extends Topic
{
    private function __construct() {}

    /**
     * @param int         $partition
     * @param int         $msgflags
     * @param string|null $payload
     * @param string|null $key
     * @param string|null $msg_opaque
     *
     * @return void
     */
    public function produce($partition, $msgflags, $payload = null, $key = null, $msg_opaque = null) {}

    /**
     * @param int         $partition
     * @param int         $msgflags
     * @param string|null $payload
     * @param string|null $key
     * @param array|null  $headers
     * @param int         $timestamp_ms
     * @param string|null $msg_opaque
     *
     * @return void
     */
    public function producev($partition, $msgflags, $payload = null, $key = null, $headers = null, $timestamp_ms = 0, $msg_opaque = null) {}
}
