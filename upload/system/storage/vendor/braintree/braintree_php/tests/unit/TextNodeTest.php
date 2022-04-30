<?php
namespace Test\Unit;

require_once dirname(__DIR__) . '/Setup.php';

use Test\Setup;
use Braintree;

class TextNodeTest extends Setup
{
  public function testIs()
  {
      $node = new Braintree\TextNode('field');
      $node->is('value');
      $this->assertEquals(['is' => 'value'], $node->toParam());
  }

  public function testIsNot()
  {
      $node = new Braintree\TextNode('field');
      $node->isNot('value');
      $this->assertEquals(['is_not' => 'value'], $node->toParam());
  }

  public function testStartsWith()
  {
      $node = new Braintree\TextNode('field');
      $node->startsWith('beginning');
      $this->assertEquals(['starts_with' => 'beginning'], $node->toParam());
  }

  public function testEndsWith()
  {
      $node = new Braintree\TextNode('field');
      $node->endsWith('end');
      $this->assertEquals(['ends_with' => 'end'], $node->toParam());
  }

  public function testContains()
  {
      $node = new Braintree\TextNode('field');
      $node->contains('middle');
      $this->assertEquals(['contains' => 'middle'], $node->toParam());
  }
}
