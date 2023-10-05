<?php

declare(strict_types=1);

/*
 * This file is part of Twig.
 *
 * (c) 2010-2019 Fabien Potencier
 * (c) 2019-2021 phpMyAdmin contributors
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PhpMyAdmin\Tests\Twig\Extensions\Node;

use PhpMyAdmin\Twig\Extensions\Node\TransNode;
use Twig\Node\Expression\ConstantExpression;
use Twig\Node\Expression\NameExpression;
use Twig\Node\Node;
use Twig\Node\PrintNode;
use Twig\Node\TextNode;
use Twig\Test\NodeTestCase;

use function sprintf;

class MoTranslatorTransTest extends NodeTestCase
{
    public static function setUpBeforeClass(): void
    {
        TransNode::$notesLabel = '// l10n: ';
        TransNode::$enableMoTranslator = true;
    }

    public static function tearDownAfterClass(): void
    {
        TransNode::$notesLabel = '// notes: ';
        TransNode::$enableMoTranslator = false;
    }

    public function testFullConstructor(): void
    {
        $count = new ConstantExpression(12, 0);
        $body = new Node([
            new TextNode('Hello', 0),
        ], [], 0);
        $notes = new Node([
            new TextNode('notes for translators', 0),
        ], [], 0);
        $domain = new Node([
            new TextNode('mydomain', 0),
        ], [], 0);
        $context = new Node([
            new TextNode('mydomain', 0),
        ], [], 0);
        $plural = new Node([
            new TextNode('Hey ', 0),
            new PrintNode(new NameExpression('name', 0), 0),
            new TextNode(', I have ', 0),
            new PrintNode(new NameExpression('count', 0), 0),
            new TextNode(' apples', 0),
        ], [], 0);
        $node = new TransNode($body, $plural, $count, $context, $notes, $domain, 0);

        $this->assertEquals($body, $node->getNode('body'));
        $this->assertEquals($count, $node->getNode('count'));
        $this->assertEquals($plural, $node->getNode('plural'));
        $this->assertEquals($notes, $node->getNode('notes'));
        $this->assertEquals($domain, $node->getNode('domain'));
        $this->assertEquals($context, $node->getNode('context'));
    }

    /**
     * @return array[]
     */
    public function getTests(): array
    {
        $tests = [];

        $body = new NameExpression('foo', 0);
        $domain = new Node([
            new TextNode('coredomain', 0),
        ], [], 0);
        $node = new TransNode($body, null, null, null, null, $domain, 0);
        $tests[] = [$node, sprintf('echo _dgettext("coredomain", %s);', $this->getVariableGetter('foo'))];

        $body = new NameExpression('foo', 0);
        $domain = new Node([
            new TextNode('coredomain', 0),
        ], [], 0);
        $context = new Node([
            new TextNode('The context', 0),
        ], [], 0);
        $node = new TransNode($body, null, null, $context, null, $domain, 0);
        $tests[] = [
            $node,
            sprintf('echo _dpgettext("coredomain", "The context", %s);', $this->getVariableGetter('foo')),
        ];

        $body = new Node([
            new TextNode('J\'ai ', 0),
            new PrintNode(new NameExpression('foo', 0), 0),
            new TextNode(' pommes', 0),
        ], [], 0);
        $node = new TransNode($body, null, null, null, null, null, 0);
        $tests[] = [
            $node,
            sprintf(
                'echo strtr(_gettext("J\'ai %%foo%% pommes"), array("%%foo%%" => %s, ));',
                $this->getVariableGetter('foo')
            ),
        ];

        $count = new ConstantExpression(12, 0);
        $body = new Node([
            new TextNode('Hey ', 0),
            new PrintNode(new NameExpression('name', 0), 0),
            new TextNode(', I have one apple', 0),
        ], [], 0);
        $plural = new Node([
            new TextNode('Hey ', 0),
            new PrintNode(new NameExpression('name', 0), 0),
            new TextNode(', I have ', 0),
            new PrintNode(new NameExpression('count', 0), 0),
            new TextNode(' apples', 0),
        ], [], 0);
        $node = new TransNode($body, $plural, $count, null, null, null, 0);
        $tests[] = [
            $node,
            sprintf(
                'echo strtr(_ngettext("Hey %%name%%, I have one apple", "Hey %%name%%,'
                . ' I have %%count%% apples", abs(12)), array("%%name%%" => %s,'
                . ' "%%name%%" => %s, "%%count%%" => abs(12), ));',
                $this->getVariableGetter('name'),
                $this->getVariableGetter('name')
            ),
        ];

        $body = new Node([
            new TextNode('J\'ai ', 0),
            new PrintNode(new NameExpression('foo', 0), 0),
            new TextNode(' pommes', 0),
        ], [], 0);
        $context = new Node([
            new TextNode('The context', 0),
        ], [], 0);
        $node = new TransNode($body, null, null, $context, null, null, 0);
        $tests[] = [
            $node,
            sprintf(
                'echo strtr(_pgettext("The context", "J\'ai %%foo%% pommes"), array("%%foo%%" => %s, ));',
                $this->getVariableGetter('foo')
            ),
        ];

        $count = new ConstantExpression(12, 0);
        $body = new Node([
            new TextNode('Hey ', 0),
            new PrintNode(new NameExpression('name', 0), 0),
            new TextNode(', I have one apple', 0),
        ], [], 0);
        $context = new Node([
            new TextNode('The context', 0),
        ], [], 0);
        $plural = new Node([
            new TextNode('Hey ', 0),
            new PrintNode(new NameExpression('name', 0), 0),
            new TextNode(', I have ', 0),
            new PrintNode(new NameExpression('count', 0), 0),
            new TextNode(' apples', 0),
        ], [], 0);
        $node = new TransNode($body, $plural, $count, $context, null, null, 0);
        $tests[] = [
            $node,
            sprintf(
                'echo strtr(_npgettext("The context", "Hey %%name%%, I have one apple", "Hey %%name%%,'
                . ' I have %%count%% apples", abs(12)), array("%%name%%" => %s,'
                . ' "%%name%%" => %s, "%%count%%" => abs(12), ));',
                $this->getVariableGetter('name'),
                $this->getVariableGetter('name')
            ),
        ];

        $body = new Node([
            new TextNode('J\'ai ', 0),
            new PrintNode(new NameExpression('foo', 0), 0),
            new TextNode(' pommes', 0),
        ], [], 0);
        $context = new Node([
            new TextNode('The context', 0),
        ], [], 0);
        $domain = new Node([
            new TextNode('mydomain', 0),
        ], [], 0);
        $node = new TransNode($body, null, null, $context, null, $domain, 0);
        $tests[] = [
            $node,
            sprintf(
                'echo strtr(_dpgettext("mydomain", "The context", "J\'ai %%foo%% pommes"), array("%%foo%%" => %s, ));',
                $this->getVariableGetter('foo')
            ),
        ];

        $count = new ConstantExpression(12, 0);
        $body = new Node([
            new TextNode('Hey ', 0),
            new PrintNode(new NameExpression('name', 0), 0),
            new TextNode(', I have one apple', 0),
        ], [], 0);
        $context = new Node([
            new TextNode('The context', 0),
        ], [], 0);
        $domain = new Node([
            new TextNode('mydomain', 0),
        ], [], 0);
        $plural = new Node([
            new TextNode('Hey ', 0),
            new PrintNode(new NameExpression('name', 0), 0),
            new TextNode(', I have ', 0),
            new PrintNode(new NameExpression('count', 0), 0),
            new TextNode(' apples', 0),
        ], [], 0);
        $node = new TransNode($body, $plural, $count, $context, null, $domain, 0);
        $tests[] = [
            $node,
            sprintf(
                'echo strtr(_dnpgettext("mydomain", "The context", "Hey %%name%%, I have one apple",'
                . ' "Hey %%name%%, I have %%count%% apples", abs(12)), array("%%name%%" => %s,'
                . ' "%%name%%" => %s, "%%count%%" => abs(12), ));',
                $this->getVariableGetter('name'),
                $this->getVariableGetter('name')
            ),
        ];

        return $tests;
    }
}
