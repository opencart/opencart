<?php
namespace Braintree;

use finfo;

/**
 * Braintree GraphQL Client
 * process GraphQL requests using curl
 */
class GraphQL extends Http
{
    public function __construct($config)
    {
        parent::__construct($config);
    }

    public function graphQLHeaders()
    {
        return [
            'Accept: application/json',
            'Braintree-Version: ' . Configuration::GRAPHQL_API_VERSION,
            'Content-Type: application/json',
            'User-Agent: Braintree PHP Library ' . Version::get(),
            'X-ApiVersion: ' . Configuration::API_VERSION
        ];
    }

    public function request($definition, $variables)
    {
        $graphQLRequest = ["query" => $definition];
        if ($variables) {
            $graphQLRequest["variables"] = $variables;
        }

        $response = $this->_doUrlRequest('POST', $this->_config->graphQLBaseUrl(), json_encode($graphQLRequest), null, $this->graphQLHeaders());

        $result = json_decode($response["body"], true);
        Util::throwGraphQLResponseException($result);

        return $result;
    }
}

class_alias('Braintree\GraphQL', 'Braintree_GraphQL');
