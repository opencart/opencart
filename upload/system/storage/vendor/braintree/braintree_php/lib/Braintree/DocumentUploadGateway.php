<?php
namespace Braintree;

use InvalidArgumentException;

/**
 * Braintree DisputeGateway module
 * PHP Version 5
 * Creates and manages Braintree Disputes
 *
 * @package   Braintree
 */
class DocumentUploadGateway
{
    /**
     * @var Gateway
     */
    private $_gateway;

    /**
     * @var Configuration
     */
    private $_config;

    /**
     * @var Http
     */
    private $_http;

    /**
     * @param Gateway $gateway
     */
    public function __construct($gateway)
    {
        $this->_gateway = $gateway;
        $this->_config = $gateway->config;
        $this->_config->assertHasAccessTokenOrKeys();
        $this->_http = new Http($gateway->config);
    }

    /* public class methods */

    /**
     * Accepts a dispute, given a dispute ID
     *
     * @param string $id
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

    public static function createSignature()
    {
        return [
            'file', 'kind'
        ];
    }
}
class_alias('Braintree\DocumentUploadGateway', 'Braintree_DocumentUploadGateway');
