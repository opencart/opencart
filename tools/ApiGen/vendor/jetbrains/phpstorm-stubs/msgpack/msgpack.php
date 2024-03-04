<?php

// Start of msgpack 0.5.0

const MESSAGEPACK_OPT_PHPONLY = -1001;

/**
 * Serialize a variable into msgpack format
 * @param mixed $value
 * @return string
 */
function msgpack_serialize($value) {}

/**
 * Unserialize $str
 * @param string $str
 * @param null|array|string|object $object <p>
 *  Undocumented template parameter
 * </p>
 * @return mixed
 */
function msgpack_unserialize($str, $object = null) {}

/**
 * Alias of msgpack_serialize
 * @param mixed $value
 * @return string
 */
function msgpack_pack($value) {}

/**
 * Alias of msgpack_unserialize
 * @param string $str
 * @param null|array|string|object $object <p>
 *  Undocumented template parameter
 *  <p>
 * @return mixed
 */
function msgpack_unpack($str, $object = null) {}

class MessagePack
{
    public const OPT_PHPONLY = -1001;

    /**
     * @param $opt [optional]
     */
    public function __construct($opt) {}

    public function setOption($option, $value) {}

    public function pack($value) {}

    /**
     * @param $str
     * @param $object [optional]
     */
    public function unpack($str, $object) {}

    public function unpacker() {}
}

class MessagePackUnpacker
{
    /**
     * @param $opt [optional]
     */
    public function __construct($opt) {}

    public function __destruct() {}

    public function setOption($option, $value) {}

    public function feed($str) {}

    /**
     * @param $str [optional]
     * @param $offset [optional]
     */
    public function execute($str, &$offset) {}

    /**
     * @param $object [optional]
     */
    public function data($object) {}

    public function reset() {}
}
