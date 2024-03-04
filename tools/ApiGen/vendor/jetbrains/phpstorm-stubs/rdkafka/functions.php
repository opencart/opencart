<?php

use JetBrains\PhpStorm\Deprecated;

/**
 * Returns the full list of error codes.
 * @return array
 */
function rd_kafka_get_err_descs() {}

/**
 * Retrieve the current number of threads in use by librdkafka.
 * @return int
 */
function rd_kafka_thread_cnt() {}

/**
 * @param int $err Error code
 *
 * @return string Returns the error as a string.
 */
function rd_kafka_err2str($err) {}

/**
 * @param int $errnox A system errno
 *
 * @return int Returns a kafka error code as an integer.
 */
#[Deprecated]
function rd_kafka_errno2err($errnox) {}

/**
 * @return int Returns the system errno as an integer.
 */
#[Deprecated]
function rd_kafka_errno() {}

/**
 * @param int $cnt
 *
 * @return int Returns the special offset as an integer.
 */
function rd_kafka_offset_tail($cnt) {}
