<?php

namespace RdKafka;

class Message
{
    /**
     * @var int
     */
    public $err;

    /**
     * @var string
     */
    public $topic_name;

    /**
     * @var int
     */
    public $timestamp;

    /**
     * @var int
     */
    public $partition;

    /**
     * @var string
     */
    public $payload;

    /**
     * @var int
     */
    public $len;

    /**
     * @var string
     */
    public $key;

    /**
     * @var int
     */
    public $offset;

    /**
     * @var array
     */
    public $headers;
    public $opaque;

    /**
     * @return string
     */
    public function errstr() {}
}
