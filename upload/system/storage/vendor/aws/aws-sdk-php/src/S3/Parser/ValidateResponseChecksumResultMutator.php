<?php

namespace Aws\S3\Parser;

use Aws\Api\Service;
use Aws\CommandInterface;
use Aws\ResultInterface;
use Aws\S3\CalculatesChecksumTrait;
use Aws\S3\Exception\S3Exception;
use Psr\Http\Message\ResponseInterface;

/**
 * A custom s3 result mutator that validates the response checksums.
 *
 * @internal
 */
final class ValidateResponseChecksumResultMutator implements S3ResultMutator
{
    use CalculatesChecksumTrait;
    /** @var Service $api */
    private $api;

    /**
     * @param Service $api
     */
    public function __construct(Service $api)
    {
        $this->api = $api;
    }

    /**
     * @param ResultInterface $result
     * @param CommandInterface|null $command
     * @param ResponseInterface|null $response
     *
     * @return ResultInterface
     */
    public function __invoke(
        ResultInterface $result,
        ?CommandInterface $command = null,
        ?ResponseInterface $response = null
    ): ResultInterface
    {
        $operation = $this->api->getOperation($command->getName());
        // Skip this middleware if the operation doesn't have an httpChecksum
        $checksumInfo = empty($operation['httpChecksum'])
            ? null
            : $operation['httpChecksum'];;
        if (null === $checksumInfo) {
            return $result;
        }

        // Skip this middleware if the operation doesn't send back a checksum,
        // or the user doesn't opt in
        $checksumModeEnabledMember = $checksumInfo['requestValidationModeMember'] ?? "";
        $checksumModeEnabled = $command[$checksumModeEnabledMember] ?? "";
        $responseAlgorithms = $checksumInfo['responseAlgorithms'] ?? [];
        if (empty($responseAlgorithms)
            || strtolower($checksumModeEnabled) !== "enabled") {
            return $result;
        }

        if (extension_loaded('awscrt')) {
            $checksumPriority = ['CRC32C', 'CRC32', 'SHA1', 'SHA256'];
        } else {
            $checksumPriority = ['CRC32', 'SHA1', 'SHA256'];
        }

        $checksumsToCheck = array_intersect(
            $responseAlgorithms,
            $checksumPriority
        );
        $checksumValidationInfo = $this->validateChecksum(
            $checksumsToCheck,
            $response
        );
        if ($checksumValidationInfo['status'] == "SUCCEEDED") {
            $result['ChecksumValidated'] = $checksumValidationInfo['checksum'];
        } elseif ($checksumValidationInfo['status'] == "FAILED") {
            // Ignore failed validations on GetObject if it's a multipart get
            // which returned a full multipart object
            if ($command->getName() === "GetObject"
                && !empty($checksumValidationInfo['checksumHeaderValue'])
            ) {
                $headerValue = $checksumValidationInfo['checksumHeaderValue'];
                $lastDashPos = strrpos($headerValue, '-');
                $endOfChecksum = substr($headerValue, $lastDashPos + 1);
                if (is_numeric($endOfChecksum)
                    && intval($endOfChecksum) > 1
                    && intval($endOfChecksum) < 10000) {
                    return $result;
                }
            }

            throw new S3Exception(
                "Calculated response checksum did not match the expected value",
                $command
            );
        }

        return $result;
    }

    /**
     * @param $checksumPriority
     * @param ResponseInterface $response
     *
     * @return array
     */
    private function validateChecksum(
        $checksumPriority,
        ResponseInterface $response
    ): array
    {
        $checksumToValidate = $this->chooseChecksumHeaderToValidate(
            $checksumPriority,
            $response
        );
        $validationStatus = "SKIPPED";
        $checksumHeaderValue = null;
        if (!empty($checksumToValidate)) {
            $checksumHeaderValue = $response->getHeader(
                'x-amz-checksum-' . $checksumToValidate
            );
            if (isset($checksumHeaderValue)) {
                $checksumHeaderValue = $checksumHeaderValue[0];
                $calculatedChecksumValue = $this->getEncodedValue(
                    $checksumToValidate,
                    $response->getBody()
                );
                $validationStatus = $checksumHeaderValue == $calculatedChecksumValue
                    ? "SUCCEEDED"
                    : "FAILED";
            }
        }
        return [
            "status" => $validationStatus,
            "checksum" => $checksumToValidate,
            "checksumHeaderValue" => $checksumHeaderValue,
        ];
    }

    /**
     * @param $checksumPriority
     * @param ResponseInterface $response
     *
     * @return string
     */
    private function chooseChecksumHeaderToValidate(
        $checksumPriority,
        ResponseInterface $response
    ):? string
    {
        foreach ($checksumPriority as $checksum) {
            $checksumHeader = 'x-amz-checksum-' . $checksum;
            if ($response->hasHeader($checksumHeader)) {
                return $checksum;
            }
        }

        return null;
    }
}
