<?php

namespace Braintree;

/**
 * Braintree CreditCardVerificationGateway module
 * Creates and manages CreditCardVerifications
 */
class CreditCardVerificationGateway
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
     * Creates a credit card verification  using the given +attributes+.
     *
     * @param array $attributes containing request parameters
     *
     * @return Result\Successful|Result\Error
     */
    public function create($attributes)
    {
        $queryPath = $this->_config->merchantPath() . "/verifications";
        $response = $this->_http->post($queryPath, ['verification' => $attributes]);
        return $this->_verifyGatewayResponse($response);
    }

    private function _verifyGatewayResponse($response)
    {

        if (isset($response['verification'])) {
            return new Result\Successful(
                CreditCardVerification::factory($response['verification'])
            );
        } elseif (isset($response['apiErrorResponse'])) {
            return new Result\Error($response['apiErrorResponse']);
        } else {
            throw new Exception\Unexpected(
                "Expected transaction or apiErrorResponse"
            );
        }
    }

    /**
     * Retrieve a credit card verification
     *
     * @param array $query search parameters
     * @param array $ids   of verifications to search
     *
     * @return Array of CreditCardVerification objects
     */
    public function fetch($query, $ids)
    {
        $criteria = [];
        foreach ($query as $term) {
            $criteria[$term->name] = $term->toparam();
        }
        $criteria["ids"] = CreditCardVerificationSearch::ids()->in($ids)->toparam();
        $path = $this->_config->merchantPath() . '/verifications/advanced_search';
        $response = $this->_http->post($path, ['search' => $criteria]);

        return Util::extractattributeasarray(
            $response['creditCardVerifications'],
            'verification'
        );
    }

    /**
     * Returns a ResourceCollection of customers matching the search query.
     *
     * @param mixed $query search query
     *
     * @return ResourceCollection
     */
    public function search($query)
    {
        $criteria = [];
        foreach ($query as $term) {
            $criteria[$term->name] = $term->toparam();
        }

        $path = $this->_config->merchantPath() . '/verifications/advanced_search_ids';
        $response = $this->_http->post($path, ['search' => $criteria]);
        $pager = [
            'object' => $this,
            'method' => 'fetch',
            'methodArgs' => [$query]
            ];

        return new ResourceCollection($response, $pager);
    }
}
