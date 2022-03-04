<?php

namespace Braintree;

use InvalidArgumentException;

/**
 * Upload documents to Braintree in exchange for a DocumentUpload object.
 *
 * An example of creating a document upload with all available fields:
 *      $result = Braintree\DocumentUpload::create([
 *          "kind" => Braintree\DocumentUpload::EVIDENCE_DOCUMENT,
 *          "file" => $pngFile
 *      ]);
 *
 * See our {@link https://developer.paypal.com/braintree/docs/reference/response/document-upload developer docs} for information on attributes
 */
class DocumentUpload extends Base
{
    /* DocumentUpload Kind */
    const EVIDENCE_DOCUMENT = "evidence_document";

    protected function _initialize($documentUploadAttribs)
    {
        $this->_attributes = $documentUploadAttribs;
    }

    /**
     * Creates a DocumentUpload object
     *
     * @param mixed $params containing:
     *                      kind - The kind of document
     *                      file - The open file to upload
     *
     * @throws InvalidArgumentException if the params are not expected
     *
     * @return Result\Successful|Result\Error
     */
    public static function create($params)
    {
        return Configuration::gateway()->documentUpload()->create($params);
    }

    /**
     * Creates an instance of a DocumentUpload from given attributes
     *
     * @param array $attributes response object attributes
     *
     * @return DocumentUpload
     */
    public static function factory($attributes)
    {
        $instance = new self();
        $instance->_initialize($attributes);
        return $instance;
    }
}
