<?php

namespace Braintree;

use InvalidArgumentException;

/**
 * Braintree DisputeGateway module
 * Creates and manages Braintree Disputes
 */
class DocumentUploadGateway
{
    private $_gateway;
    private $_config;
    private $_http;

    // phpcs:ignore PEAR.Commenting.FunctionComment.Missing
    public function __construct($gateway)
    {
        $this->_gateway = $gateway;
        $this->_config = $gateway->config;
        $this->_config->assertHasAccessTokenOrKeys();
        $this->_http = new Http($gateway->config);
    }

    /**
     * Accepts a dispute, given a dispute ID
     *
     * @param mixed $params containing:
     *                      kind - The kind of document
     *                      file - The open file to upload
     *
     * @throws InvalidArgumentException if the params are not expected
     *
     * @return Result\Successful|Result\Error
     */
    public function create($params)
    {
        Util::verifyKeys(self::createSignature(), $params);

        $file = $params['file'];

        if (!is_resource($file)) {
            throw new InvalidArgumentException('file must be a stream resource');
        }

        $payload = [
            'document_upload[kind]' => $params['kind']
        ];
        $path = $this->_config->merchantPath() . '/document_uploads/';
        $response = $this->_http->postMultipart($path, $payload, $file);

        if (isset($response['apiErrorResponse'])) {
            return new Result\Error($response['apiErrorResponse']);
        }

        if (isset($response['documentUpload'])) {
            $documentUpload = DocumentUpload::factory($response['documentUpload']);
            return new Result\Successful($documentUpload);
        }
    }

    /*
     * Returns keys that are acceptable for create requests
     *
     * @see create
     */
    public static function createSignature()
    {
        return [
            'file', 'kind'
        ];
    }
}
