<?php
namespace Test\Unit;

require_once dirname(__DIR__) . '/Setup.php';

use Test\Setup;
use Braintree;

class TransparentRedirectTest extends Setup
{
    public function testData_specifiesArgSeparatorAsAmpersand()
    {
        $originalSeparator = ini_get("arg_separator.output");
        ini_set("arg_separator.output", "&amp;");
        $trData = Braintree\TransparentRedirect::createCustomerData(['redirectUrl' => 'http://www.example.com']);
        ini_set("arg_separator.output", $originalSeparator);
        $this->assertFalse(strpos($trData, "&amp;"));
    }

    public function testData_doesNotClobberDefaultTimezone()
    {
        $originalZone = date_default_timezone_get();
        date_default_timezone_set('Europe/London');

        $trData = Braintree\TransparentRedirect::createCustomerData(['redirectUrl' => 'http://www.example.com']);
        $zoneAfterCall = date_default_timezone_get();
        date_default_timezone_set($originalZone);

        $this->assertEquals('Europe/London', $zoneAfterCall);
    }
}
