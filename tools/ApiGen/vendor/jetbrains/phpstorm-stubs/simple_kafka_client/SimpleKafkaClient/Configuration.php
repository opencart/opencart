<?php
declare(strict_types=1);

namespace SimpleKafkaClient;

class Configuration
{
    public function __construct() {}

    /**
     * @return array<string, mixed>
     */
    public function dump(): array {}

    /**
     * @param string $name
     * @param string $value
     */
    public function set(string $name, string $value): void {}

    /**
     * @param callable $callback
     */
    public function setErrorCb(callable $callback): void {}

    /**
     * @param callable $callback
     */
    public function setDrMsgCb(callable $callback): void {}

    /**
     * @param callable $callback
     */
    public function setStatsCb(callable $callback): void {}

    /**
     * @param callable $callback
     */
    public function setRebalanceCb(callable $callback): void {}

    /**
     * @param callable $callback
     */
    public function setOffsetCommitCb(callable $callback): void {}

    /**
     * @param callable $callback
     */
    public function setLogCb(callable $callback): void {}

    /**
     * @param callable $callback
     */
    public function setOAuthBearerTokenRefreshCb(callable $callback): void {}
}
