<?php

namespace Cardinity\Method;

/**
 * Method interface for building queries for API.
 */
interface MethodInterface
{
    /**
     * HTTP method POST
     */
    const POST = 'POST';

    /**
     * HTTP method PATCH
     */
    const PATCH = 'PATCH';

    /**
     * HTTP method GET
     */
    const GET = 'GET';

    /**
     * HTTP method to use
     * @return string
     */
    public function getMethod();

    /**
     * API action name, part of full API request url
     * @return string
     */
    public function getAction();

    /**
     * Result object for this query result
     * @return ResultObjectInterface
     */
    public function createResultObject();

    /**
     * Validation constraints for fields
     * @return \Symfony\Component\Validator\Constraints\Collection
     */
    public function getValidationConstraints();

    /**
     * Field and values association of object attributes
     * @return array
     */
    public function getAttributes();
}
