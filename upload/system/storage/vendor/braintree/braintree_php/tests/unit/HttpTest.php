<?php
namespace Test\Unit;

require_once dirname(__DIR__) . '/Setup.php';

use Test\Setup;
use Braintree;

class HttpTest extends Setup
{
    public function testMalformedNoSsl()
    {
        try {
            Braintree\Configuration::environment('development');
            $this->setExpectedException('Braintree\Exception\Connection', null, 3);
            $http = new Braintree\Http(Braintree\Configuration::$global);
            $http->_doUrlRequest('get', '/a_malformed_url');
        } catch (Braintree\Exception $e) {
            throw $e;
        }
    }

    public function testMalformedUrlUsingSsl()
    {
        try {
            Braintree\Configuration::environment('sandbox');
            $this->setExpectedException('Braintree\Exception\SSLCertificate', null, 3);
            $http = new Braintree\Http(Braintree\Configuration::$global);
            $http->_doUrlRequest('get', '/a_malformed_url_using_ssl');
        } catch (Braintree\Exception $e) {
            Braintree\Configuration::environment('development');
            throw $e;
        }
        Braintree\Configuration::environment('development');
    }

    public function testSSLVersionError()
    {
        try {
            Braintree\Configuration::environment('sandbox');
            Braintree\Configuration::sslVersion(3);
            $this->setExpectedException('Braintree\Exception\SSLCertificate', null, 35);
            $http = new Braintree\Http(Braintree\Configuration::$global);
            $http->get('/');
        } catch (Braintree\Exception $e) {
            Braintree\Configuration::environment('development');
            Braintree\Configuration::sslVersion(null);
            throw $e;
        }
        Braintree\Configuration::environment('development');
        Braintree\Configuration::sslVersion(null);
    }

    public function testGoodRequest()
    {
        Braintree\Configuration::environment('development');
        $http = new Braintree\Http(Braintree\Configuration::$global);
        $http->_doUrlRequest('get', 'http://example.com');
    }
}