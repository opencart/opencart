<?php
namespace Test\Unit;

require_once dirname(__DIR__) . '/Setup.php';

use Test\Setup;
use Braintree;

class RangeNodeTest extends Setup
{
    public function testGreaterThanOrEqualTo()
    {
        $node = new Braintree\RangeNode('field');
        $node->greaterThanOrEqualTo('smallest');
        $this->assertEquals(['min' => 'smallest'], $node->toParam());
    }

    public function testLessThanOrEqualTo()
    {
        $node = new Braintree\RangeNode('field');
        $node->lessThanOrEqualTo('biggest');
        $this->assertEquals(['max' => 'biggest'], $node->toParam());
    }

    public function testBetween()
    {
        $node = new Braintree\RangeNode('field');
        $node->between('alpha', 'omega');
        $this->assertEquals(['min' => 'alpha', 'max' => 'omega'], $node->toParam());
    }

    public function testIs()
    {
        $node = new Braintree\RangeNode('field');
        $node->is('something');
        $this->assertEquals(['is' => 'something'], $node->toParam());
    }
}
