<?php
declare(strict_types=1);

/**
 * Returns the name of the error
 *
 * @param int $errorCode Error code
 * @return string
 */
function kafka_err2name(int $errorCode): string {}

/**
 * Returns the error message of an error code
 *
 * @param int $errorCode Error code
 * @return string
 */
function kafka_err2str(int $errorCode): string {}

/**
 * Returns the full list of error codes.
 *
 * @return array<int, mixed>
 */
function kafka_get_err_descs(): array {}

/**
 * Returns an offset value that is $offset before the tail of the topic
 *
 * @param int $offset
 * @return int
 */
function kafka_offset_tail(int $offset): int {}

/**
 * Retrieve the current number of threads in use by librdkafka.
 *
 * @return int
 */
function kafka_thread_cnt(): int {}
