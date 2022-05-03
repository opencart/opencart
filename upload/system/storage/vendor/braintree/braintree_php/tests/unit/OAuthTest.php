<?php
namespace Test\Unit;

require_once dirname(__DIR__) . '/Setup.php';

use Test\Setup;
use Braintree;

class OAuthTest extends Setup
{
    protected $gateway;

    public function setUp()
    {
        $this->gateway = new Braintree\Gateway([
            'clientId' => 'client_id$development$integration_client_id',
            'clientSecret' => 'client_secret$development$integration_client_secret'
        ]);
    }

    public function testMapInvalidGrantCodeToOldError()
    {
        $result = $this->_buildResult([
            'code' => '93801',
            'message' => 'Invalid grant: code not found'
        ]);

        $this->gateway->oauth()->_mapError($result);

        $this->assertEquals($result->error, 'invalid_grant');
        $this->assertEquals($result->errorDescription, 'code not found');
    }

    public function testMapInvalidCredentialsCodeToOldError()
    {
        $result = $this->_buildResult([
            'code' => '93802',
            'message' => 'Invalid credentials: wrong client id or secret'
        ]);

        $this->gateway->oauth()->_mapError($result);

        $this->assertEquals($result->error, 'invalid_credentials');
        $this->assertEquals($result->errorDescription, 'wrong client id or secret');
    }

    public function testMapInvalidScopeCodeToOldError()
    {
        $result = $this->_buildResult([
            'code' => '93803',
            'message' => 'Invalid scope: scope is invalid'
        ]);

        $this->gateway->oauth()->_mapError($result);

        $this->assertEquals($result->error, 'invalid_scope');
        $this->assertEquals($result->errorDescription, 'scope is invalid');
    }

    protected function _buildResult($error)
    {
        return new Braintree\Result\Error([
            'errors' => [
                'errors' => [],
                'credentials' => [
                    'errors' => [$error]
                ]
            ]
        ]);
    }
}
