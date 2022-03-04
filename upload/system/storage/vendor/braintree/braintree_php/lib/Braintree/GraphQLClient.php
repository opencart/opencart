<?php

namespace Braintree;

/**
 * Braintree GraphQL Client
 * process GraphQL requests using curl
 */
class GraphQLClient
{
    // phpcs:ignore PEAR.Commenting.FunctionComment.Missing
    public function __construct($config)
    {
        $this->_service = new GraphQL($config);
    }

    /*
     * Make a GraphQL API request
     *
     * @param object $definition of the query
     * @param object $variables optional
     *
     * @return object result
     */
    public function query($definition, $variables = null)
    {
        return $this->_service->request($definition, $variables);
    }
}
