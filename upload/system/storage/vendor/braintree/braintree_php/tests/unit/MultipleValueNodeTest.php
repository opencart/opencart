<?php
namespace Test\Unit;

require_once dirname(__DIR__) . '/Setup.php';

use Test\Setup;
use Braintree;

class MultipleValueNodeTest extends Setup
{
    public function testIs()
    {
        $node = new Braintree\MultipleValueNode('field');
        $node->is('value');
        $this->assertEquals(['value'], $node->toParam());
    }

    public function testIn()
    {
        $node = new Braintree\MultipleValueNode('field');
        $node->in(['firstValue', 'secondValue']);
        $this->assertEquals(['firstValue', 'secondValue'], $node->toParam());
    }
}
