<?php
namespace Test\Integration;

require_once dirname(__DIR__) . '/Setup.php';

use Test\Setup;
use Braintree;

class GraphQLTest extends Setup
{
    public function testGraphQLPing()
    {
        Braintree\Configuration::environment('development');
        $graphQL = new Braintree\GraphQL(Braintree\Configuration::$global);
        $definition = "query { ping }";
        $response = $graphQL->request($definition, NULL);

        $this->assertEquals('pong', $response["data"]["ping"]);
    }

    public function testGraphQLProductionSSL()
    {
        Braintree\Configuration::environment('production');
        $graphQL = new Braintree\GraphQL(Braintree\Configuration::$global);
        $definition = "query { ping }";

        $this->setExpectedException('Braintree\Exception\Authentication');
        $response = $graphQL->request($definition, NULL);
    }

    public function testGraphQLSandboxSSL()
    {
        Braintree\Configuration::environment('sandbox');
        $graphQL = new Braintree\GraphQL(Braintree\Configuration::$global);
        $definition = "query { ping }";

        $this->setExpectedException('Braintree\Exception\Authentication');
        $response = $graphQL->request($definition, NULL);
    }

    public function testGraphQLWithVariableInputs()
    {
        Braintree\Configuration::environment('development');
        $graphQL = new Braintree\GraphQL(Braintree\Configuration::$global);
        $definition = '
mutation CreateClientToken($input: CreateClientTokenInput!) {
    createClientToken(input: $input) {
        clientMutationId
        clientToken
    }
}';
        $variables = [
          "input" => [
            "clientMutationId" => "abc123",
            "clientToken" => [
              "merchantAccountId" => "ABC123"
            ]
          ]
        ];
        $response = $graphQL->request($definition, $variables);

        $this->assertArrayNotHasKey("errors", $response);
        $this->assertTrue(is_string($response["data"]["createClientToken"]["clientToken"]));
    }

    public function testGraphQLWithVariableInputsCanReturnParsableErrors()
    {
        Braintree\Configuration::environment('development');
        $graphQL = new Braintree\GraphQL(Braintree\Configuration::$global);
        $definition = '
query TransactionLevelFeeReport($date: Date!, $merchantAccountId: ID) {
    report {
        transactionLevelFees(date: $date, merchantAccountId: $merchantAccountId) {
            url
        }
    }
}';
        $variables = [
            "date" => "2018-01-01",
            "merchantAccountId" => "some_merchant"
        ];
        $response = $graphQL->request($definition, $variables);

        $this->assertEquals("Invalid merchant account id: some_merchant", $response["errors"][0]["message"]);
    }
}
