<?php
declare(strict_types=1);

namespace SimpleKafkaClient;

class KafkaErrorException extends Exception
{
    /**
     * KafkaErrorException constructor.
     * @param string $message
     * @param int $code
     * @param string $error_string
     * @param bool $isFatal
     * @param bool $isRetriable
     * @param bool $transactionRequiresAbort
     */
    public function __construct(
        string $message,
        int $code,
        string $error_string,
        bool $isFatal,
        bool $isRetriable,
        bool $transactionRequiresAbort
    ) {}

    public function getErrorString(): string {}

    public function isFatal(): bool {}

    public function isRetriable(): bool {}

    public function transactionRequiresAbort(): bool {}
}
