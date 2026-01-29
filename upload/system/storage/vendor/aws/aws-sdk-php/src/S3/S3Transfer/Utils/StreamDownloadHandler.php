<?php

namespace Aws\S3\S3Transfer\Utils;

use Aws\S3\S3Transfer\Progress\AbstractTransferListener;
use GuzzleHttp\Psr7\Utils;
use Psr\Http\Message\StreamInterface;

final class StreamDownloadHandler extends AbstractDownloadHandler
{
    /** @var StreamInterface|null */
    private ?StreamInterface $stream;

    /**
     * @param StreamInterface|null $stream
     */
    public function __construct(?StreamInterface $stream = null)
    {
        $this->stream = $stream;
    }

    /**
     * @param array $context
     *
     * @return void
     */
    public function transferInitiated(array $context): void
    {
        if (is_null($this->stream)) {
            $this->stream = Utils::streamFor(
                fopen('php://temp', 'w+')
            );
        } else {
            $this->stream->seek($this->stream->getSize());
        }
    }

    /**
     * @inheritDoc
     */
    public function bytesTransferred(array $context): bool
    {
        $snapshot = $context[AbstractTransferListener::PROGRESS_SNAPSHOT_KEY];
        $response = $snapshot->getResponse();
        $partBody = $response['Body'];
        if ($partBody->isSeekable()) {
            $partBody->rewind();
        }

        Utils::copyToStream(
            $partBody,
            $this->stream
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
        $this->stream->rewind();
    }

    /**
     * @param array $context
     *
     * @return void
     */
    public function transferFail(array $context): void
    {
        $this->stream->close();
        $this->stream = null;
    }

    /**
     * @inheritDoc
     *
     * @return StreamInterface
     */
    public function getHandlerResult(): StreamInterface
    {
        return $this->stream;
    }
}
