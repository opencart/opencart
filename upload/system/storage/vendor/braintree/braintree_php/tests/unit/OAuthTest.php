<?php
require_once __DIR__ . '/../TestHelper.php';

class Braintree_OAuthTest extends PHPUnit_Framework_TestCase
{
    protected $gateway;

    public function setUp()
    {
        $this->gateway = new Braintree_Gateway(array(
            'clientId' => 'client_id$development$integration_client_id',
            'clientSecret' => 'client_secret$development$integration_client_secret'
        ));
    }

    public function testMapInvalidGrantCodeToOldError()
    {
        $result = $this->_buildResult(array(
            'code' => '93801',
            'message' => 'Invalid grant: code not found'
        ));

        $this->gateway->oauth()->_mapError($result);

        $this->assertEquals($result->error, 'invalid_grant');
        $this->assertEquals($result->errorDescription, 'code not found');
    }

    public function testMapInvalidCredentialsCodeToOldError()
    {
        $result = $this->_buildResult(array(
            'code' => '93802',
            'message' => 'Invalid credentials: wrong client id or secret'
        ));

        $this->gateway->oauth()->_mapError($result);

        $this->assertEquals($result->error, 'invalid_credentials');
        $this->assertEquals($result->errorDescription, 'wrong client id or secret');
    }

    public function testMapInvalidScopeCodeToOldError()
    {
        $result = $this->_buildResult(array(
            'code' => '93803',
            'message' => 'Invalid scope: scope is invalid'
        ));

        $this->gateway->oauth()->_mapError($result);

        $this->assertEquals($result->error, 'invalid_scope');
        $this->assertEquals($result->errorDescription, 'scope is invalid');
    }

    protected function _buildResult($error)
    {
        return new Braintree_Result_Error(array(
            'errors' => array(
                'errors' => array(),
                'credentials' => array(
                    'errors' => array($error)
                )
            )
        ));
    }
}
