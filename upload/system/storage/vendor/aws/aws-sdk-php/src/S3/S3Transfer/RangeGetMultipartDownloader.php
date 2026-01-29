<?php

namespace Aws\S3\S3Transfer;

use Aws\CommandInterface;
use Aws\Result;
use Aws\ResultInterface;

final class RangeGetMultipartDownloader extends AbstractMultipartDownloader
{
    /**
     * @inheritDoc
     *
     * @return CommandInterface
     */
    protected function nextCommand(): CommandInterface
    {
        if ($this->currentPartNo === 0) {
            $this->currentPartNo = 1;
        } else {
            $this->currentPartNo++;
        }

        $nextRequestArgs = $this->downloadRequestArgs;
        $partSize = $this->config['target_part_size_bytes'];
        $from = ($this->currentPartNo - 1) * $partSize;
        $to = ($this->currentPartNo * $partSize) - 1;

        if ($this->objectSizeInBytes !== 0) {
            $to = min($this->objectSizeInBytes, $to);
        }

        $nextRequestArgs['Range'] = "bytes=$from-$to";

        if ($this->config['response_checksum_validation'] === 'when_supported') {
            $nextRequestArgs['ChecksumMode'] = 'ENABLED';
        }

        if (!empty($this->eTag)) {
            $nextRequestArgs['IfMatch'] = $this->eTag;
        }

        return $this->s3Client->getCommand(
            self::GET_OBJECT_COMMAND,
            $nextRequestArgs
        );
    }

    /**
     * @inheritDoc
     *
     * @param Result $result
     *
     * @return void
     */
    protected function computeObjectDimensions(ResultInterface $result): void
    {
        // Assign object size just if needed.
        if ($this->objectSizeInBytes === 0) {
            $this->objectSizeInBytes = $this->computeObjectSizeFromContentRange(
                $result['ContentRange'] ?? ""
            );
        }

        $partSize = $this->config['target_part_size_bytes'];
        if ($this->objectSizeInBytes > $partSize) {
            $this->objectPartsCount = intval(
                ceil($this->objectSizeInBytes / $partSize)
            );
        } else {
            // Single download since partSize will be set to full object size.
            $this->objectPartsCount = 1;
            $this->currentPartNo = 1;
        }
    }
}
