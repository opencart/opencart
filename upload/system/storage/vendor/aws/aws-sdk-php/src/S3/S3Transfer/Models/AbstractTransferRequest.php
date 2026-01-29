<?php

namespace Aws\S3\S3Transfer\Models;

use Aws\S3\S3ClientInterface;
use Aws\S3\S3Transfer\Progress\AbstractTransferListener;
use InvalidArgumentException;

abstract class AbstractTransferRequest
{
    public static array $configKeys = [
        'track_progress' => 'bool',
    ];

    /** @var array  */
    protected array $listeners;

    /** @var AbstractTransferListener|null  */
    protected ?AbstractTransferListener $progressTracker;

    /** @var array */
    protected array $config;

    /** @var S3ClientInterface|null */
    private ?S3ClientInterface $s3Client;

    /**
     * @param array $listeners
     * @param AbstractTransferListener|null $progressTracker
     * @param array $config
     * @param S3ClientInterface|null $s3Client
     */
    public function __construct(
        array $listeners,
        ?AbstractTransferListener $progressTracker,
        array $config,
        ?S3ClientInterface $s3Client = null,
    ) {
        $this->listeners = $listeners;
        $this->progressTracker = $progressTracker;
        $this->config = $config;
        $this->s3Client = $s3Client;
    }

    /**
     * Get current listeners.
     *
     * @return array
     */
    public function getListeners(): array
    {
        return $this->listeners;
    }

    /**
     * Get the progress tracker.
     *
     * @return AbstractTransferListener|null
     */
    public function getProgressTracker(): ?AbstractTransferListener
    {
        return $this->progressTracker;
    }

    /**
     * @return array
     */
    public function getConfig(): array
    {
        return $this->config;
    }

    /**
     * @return S3ClientInterface|null
     */
    public function getS3Client(): ?S3ClientInterface
    {
        return $this->s3Client;
    }

    /**
     * @param array $defaultConfig
     *
     * @return void
     */
    public function updateConfigWithDefaults(array $defaultConfig): void
    {
        foreach (static::$configKeys as $key => $_) {
            if (isset($defaultConfig[$key]) && empty($this->config[$key])) {
                $this->config[$key] = $defaultConfig[$key];
            }
        }
    }

    /**
     * For validating config. By default, it provides an empty
     * implementation.
     * @return void
     */
    public function validateConfig(): void {
        foreach (static::$configKeys as $key => $type) {
            if (isset($this->config[$key])
                && !call_user_func('is_' . $type, $this->config[$key])) {
                throw new InvalidArgumentException(
                    "The provided config `$key` must be $type."
                );
            }
        }
    }
}
