<?php
namespace Test\Unit;

require_once dirname(__DIR__) . '/Setup.php';

use Test\Setup;
use Braintree;

class MultipleValueOrTextNodeTest extends Setup
{
    public function testIn()
    {
        $node = new Braintree\MultipleValueOrTextNode('field');
        $node->in(['firstValue', 'secondValue']);
        $this->assertEquals(['firstValue', 'secondValue'], $node->toParam());
    }

    public function testIs()
    {
        $node = new Braintree\MultipleValueOrTextNode('field');
        $node->is('value');
        $this->assertEquals(['is' => 'value'], $node->toParam());
    }

    public function testIsNot()
    {
        $node = new Braintree\MultipleValueOrTextNode('field');
        $node->isNot('value');
        $this->assertEquals(['is_not' => 'value'], $node->toParam());
    }

    public function testStartsWith()
    {
        $node = new Braintree\MultipleValueOrTextNode('field');
        $node->startsWith('beginning');
        $this->assertEquals(['starts_with' => 'beginning'], $node->toParam());
    }

    public function testEndsWith()
    {
        $node = new Braintree\MultipleValueOrTextNode('field');
        $node->endsWith('end');
        $this->assertEquals(['ends_with' => 'end'], $node->toParam());
    }

    public function testContains()
    {
        $node = new Braintree\MultipleValueOrTextNode('field');
        $node->contains('middle');
        $this->assertEquals(['contains' => 'middle'], $node->toParam());
    }
}
