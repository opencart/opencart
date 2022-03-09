<?php
namespace Test\Unit;

require_once dirname(__DIR__) . '/Setup.php';

use Test\Setup;
use Braintree;

class OAuthAccessRevocationTest extends Setup
{
    public function testIncludesMerchantId()
    {
        $revocation = Braintree\OAuthAccessRevocation::factory([
            'merchantId' => 'abc123xyz'
        ]);

        $this->assertEquals($revocation->merchantId, 'abc123xyz');
    }
}
