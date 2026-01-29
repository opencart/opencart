<?php

namespace Aws\S3\S3Transfer\Utils;

use Aws\S3\S3Transfer\Exception\FileDownloadException;
use Aws\S3\S3Transfer\Progress\AbstractTransferListener;

final class FileDownloadHandler extends AbstractDownloadHandler
{
    private const IDENTIFIER_LENGTH = 8;
    private const TEMP_INFIX = '.s3tmp.';

    /** @var string */
    private string $destination;

    /**
     * @var bool
     */
    private bool $failsWhenDestinationExists;

    /** @var string */
    private string $temporaryDestination;

    /**
     * @param string $destination
     * @param bool $failsWhenDestinationExists
     */
    public function __construct(
        string $destination,
        bool $failsWhenDestinationExists
    ) {
        $this->destination = $destination;
        $this->failsWhenDestinationExists = $failsWhenDestinationExists;
        $this->temporaryDestination = "";
    }

    /**
     * @return string
     */
    public function getDestination(): string
    {
        return $this->destination;
    }

    /**
     * @return bool
     */
    public function isFailsWhenDestinationExists(): bool
    {
        return $this->failsWhenDestinationExists;
    }

    /**
     * @param array $context
     *
     * @return void
     */
    public function transferInitiated(array $context): void
    {
        if ($this->failsWhenDestinationExists && file_exists($this->destination)) {
            throw new FileDownloadException(
                "The destination '$this->destination' already exists."
            );
        } elseif (is_dir($this->destination)) {
            throw new FileDownloadException(
                "The destination '$this->destination' can't be a directory."
            );
        }

        // Create directory if necessary
        $directory = dirname($this->destination);
        if (!is_dir($directory)) {
            mkdir($directory, 0777, true);
        }

        $uniqueId = self::getUniqueIdentifier();
        $temporaryName = $this->destination . self::TEMP_INFIX . $uniqueId;
        while (file_exists($temporaryName)) {
            $uniqueId = self::getUniqueIdentifier();
            $temporaryName = $this->destination . self::TEMP_INFIX . $uniqueId;
        }

        // Create the file
        file_put_contents($temporaryName, "");
        $this->temporaryDestination = $temporaryName;
    }

    /**
     * @param array $context
     *
     * @return void
     */
    public function bytesTransferred(array $context): bool
    {
        $snapshot = $context[AbstractTransferListener::PROGRESS_SNAPSHOT_KEY];
        $response = $snapshot->getResponse();
        $partBody = $response['Body'];
        if ($partBody->isSeekable()) {
            $partBody->rewind();
        }

        file_put_contents(
            $this->temporaryDestination,
            $partBody,
            FILE_APPEND
        );

        return true;
    }

    /**
     * @param array $context
     *
     * @return void
     */
    public function transferComplete(array $context): void
    {
        // Make sure the file is deleted if exists
        if (file_exists($this->destination) && is_file($this->destination)) {
            if ($this->failsWhenDestinationExists) {
                throw new FileDownloadException(
                    "The destination '$this->destination' already exists."
                );
            } else {
                unlink($this->destination);
            }
        }

        if (!rename($this->temporaryDestination, $this->destination)) {
            throw new FileDownloadException(
                "Unable to rename the file `$this->temporaryDestination` to `$this->destination`."
            );
        }
    }

    /**
     * @param array $context
     *
     * @return void
     */
    public function transferFail(array $context): void
    {
        if (file_exists($this->temporaryDestination)) {
            unlink($this->temporaryDestination);
        } elseif (file_exists($this->destination)
            && !str_contains(
                $context[self::REASON_KEY],
                "The destination '$this->destination' already exists.")
        ) {
            unlink($this->destination);
        }
    }

    /**
     * @return string
     */
    private static function getUniqueIdentifier(): string
    {
        $uniqueId = uniqid();
        if (strlen($uniqueId) > self::IDENTIFIER_LENGTH) {
            $uniqueId = substr($uniqueId, 0, self::IDENTIFIER_LENGTH);
        } else {
            $uniqueId = str_pad($uniqueId, self::IDENTIFIER_LENGTH, "0");
        }

        return $uniqueId;
    }

    /**
     * @return string
     */
    public function getHandlerResult(): string
    {
        return $this->destination;
    }
}
