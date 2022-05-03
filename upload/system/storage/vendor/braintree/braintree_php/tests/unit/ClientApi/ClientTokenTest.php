<?php
namespace Test\Unit\ClientApi;

require_once dirname(dirname(__DIR__)) . '/Setup.php';

use Test\Setup;
use Braintree;

class ClientTokenTest extends Setup
{
    public function testErrorsWhenCreditCardOptionsGivenWithoutCustomerId()
    {
        $this->setExpectedException('InvalidArgumentException', 'invalid keys: options[makeDefault]');
        Braintree\ClientToken::generate(["options" => ["makeDefault" => true]]);
    }

    public function testErrorsWhenInvalidArgumentIsSupplied()
    {
        $this->setExpectedException('InvalidArgumentException', 'invalid keys: customrId');
        Braintree\ClientToken::generate(["customrId" => "1234"]);
    }
}
