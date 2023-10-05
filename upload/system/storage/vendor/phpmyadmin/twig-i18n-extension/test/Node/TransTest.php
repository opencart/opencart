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
use Twig\Node\Expression\FilterExpression;
use Twig\Node\Expression\NameExpression;
use Twig\Node\Node;
use Twig\Node\PrintNode;
use Twig\Node\TextNode;
use Twig\Test\NodeTestCase;

use function sprintf;

class TransTest extends NodeTestCase
{
    public function testConstructor(): void
    {
        $count = new ConstantExpression(12, 0);
        $body = new Node([
            new TextNode('Hello', 0),
        ], [], 0);
        $plural = new Node([
            new TextNode('Hey ', 0),
            new PrintNode(new NameExpression('name', 0), 0),
            new TextNode(', I have ', 0),
            new PrintNode(new NameExpression('count', 0), 0),
            new TextNode(' apples', 0),
        ], [], 0);
        $node = new TransNode($body, $plural, $count, null, null, null, 0);

        $this->assertEquals($body, $node->getNode('body'));
        $this->assertEquals($count, $node->getNode('count'));
        $this->assertEquals($plural, $node->getNode('plural'));
    }

    public function testConstructorWithDomain(): void
    {
        $count = new ConstantExpression(12, 0);
        $body = new Node([
            new TextNode('Hello', 0),
        ], [], 0);
        $domain = new Node([
            new TextNode('coredomain', 0),
        ], [], 0);
        $plural = new Node([
            new TextNode('Hey ', 0),
            new PrintNode(new NameExpression('name', 0), 0),
            new TextNode(', I have ', 0),
            new PrintNode(new NameExpression('count', 0), 0),
            new TextNode(' apples', 0),
        ], [], 0);
        $node = new TransNode($body, $plural, $count, null, null, $domain, 0);

        $this->assertEquals($body, $node->getNode('body'));
        $this->assertEquals($count, $node->getNode('count'));
        $this->assertEquals($plural, $node->getNode('plural'));
        $this->assertEquals($domain, $node->getNode('domain'));
    }

    public function testEnableDebugNotEnabled(): void
    {
        $count = new ConstantExpression(5, 0);
        $body = new TextNode('There is 1 pending task', 0);
        $plural = new Node([
            new TextNode('There are ', 0),
            new PrintNode(new NameExpression('count', 0), 0),
            new TextNode(' pending tasks', 0),
        ], [], 0);
        $notes = new TextNode('Notes for translators', 0);
        TransNode::$enableAddDebugInfo = false;
        TransNode::$notesLabel = '// custom: ';
        $node = new TransNode($body, $plural, $count, null, $notes, null, 80);

        $compiler = $this->getCompiler();
        $this->assertEmpty($compiler->getDebugInfo());
        $sourceCode = $compiler->compile($node)->getSource();
        $this->assertSame(
            '// custom: Notes for translators' . "\n" . 'echo strtr(ngettext("There is 1 pending task",'
            . ' "There are %count% pending tasks", abs(5)), array("%count%" => abs(5), ));' . "\n",
            $sourceCode
        );
        $this->assertSame([], $compiler->getDebugInfo());
        TransNode::$enableAddDebugInfo = false;
        TransNode::$notesLabel = '// notes: ';
    }

    public function testEnableDebugEnabled(): void
    {
        $count = new ConstantExpression(5, 0);
        $body = new TextNode('There is 1 pending task', 0);
        $plural = new Node([
            new TextNode('There are ', 0),
            new PrintNode(new NameExpression('count', 0), 0),
            new TextNode(' pending tasks', 0),
        ], [], 0);
        $notes = new TextNode('Notes for translators', 0);

        TransNode::$enableAddDebugInfo = true;
        TransNode::$notesLabel = '// custom: ';
        $node = new TransNode($body, $plural, $count, null, $notes, null, 80);

        $compiler = $this->getCompiler();
        $this->assertEmpty($compiler->getDebugInfo());
        $sourceCode = $compiler->compile($node)->getSource();
        $this->assertSame(
            '// line 80' . "\n" . '// custom: Notes for translators' . "\n" . 'echo strtr(ngettext("There'
            . ' is 1 pending task", "There are %count% pending tasks", abs(5)), array("%count%" => abs(5), ));' . "\n",
            $sourceCode
        );
        $this->assertSame([2 => 80], $compiler->getDebugInfo());
        TransNode::$enableAddDebugInfo = false;
        TransNode::$notesLabel = '// notes: ';
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
        $tests[] = [$node, sprintf('echo dgettext("coredomain", %s);', $this->getVariableGetter('foo'))];

        $body = new NameExpression('foo', 0);
        $node = new TransNode($body, null, null, null, null, null, 0);
        $tests[] = [$node, sprintf('echo gettext(%s);', $this->getVariableGetter('foo'))];

        $body = new ConstantExpression('Hello', 0);
        $node = new TransNode($body, null, null, null, null, null, 0);
        $tests[] = [$node, 'echo gettext("Hello");'];

        $body = new Node([
            new TextNode('Hello', 0),
        ], [], 0);
        $node = new TransNode($body, null, null, null, null, null, 0);
        $tests[] = [$node, 'echo gettext("Hello");'];

        $body = new Node([
            new TextNode('J\'ai ', 0),
            new PrintNode(new NameExpression('foo', 0), 0),
            new TextNode(' pommes', 0),
        ], [], 0);
        $node = new TransNode($body, null, null, null, null, null, 0);
        $tests[] = [
            $node,
            sprintf(
                'echo strtr(gettext("J\'ai %%foo%% pommes"), array("%%foo%%" => %s, ));',
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
                'echo strtr(ngettext("Hey %%name%%, I have one apple", "Hey %%name%%, I have'
                . ' %%count%% apples", abs(12)), array("%%name%%" => %s,'
                . ' "%%name%%" => %s, "%%count%%" => abs(12), ));',
                $this->getVariableGetter('name'),
                $this->getVariableGetter('name')
            ),
        ];

        // with escaper extension set to on
        $body = new Node([
            new TextNode('J\'ai ', 0),
            new PrintNode(
                new FilterExpression(new NameExpression('foo', 0), new ConstantExpression('escape', 0), new Node(), 0),
                0
            ),
            new TextNode(' pommes', 0),
        ], [], 0);

        $node = new TransNode($body, null, null, null, null, null, 0);
        $tests[] = [
            $node,
            sprintf(
                'echo strtr(gettext("J\'ai %%foo%% pommes"), array("%%foo%%" => %s, ));',
                $this->getVariableGetter('foo')
            ),
        ];

        // with notes
        $body = new ConstantExpression('Hello', 0);
        $notes = new TextNode('Notes for translators', 0);
        $node = new TransNode($body, null, null, null, $notes, null, 0);
        $tests[] = [$node, "// notes: Notes for translators\necho gettext(\"Hello\");"];

        $body = new ConstantExpression('Hello', 0);
        $notes = new TextNode("Notes for translators\nand line breaks", 0);
        $node = new TransNode($body, null, null, null, $notes, null, 0);
        $tests[] = [$node, "// notes: Notes for translators and line breaks\necho gettext(\"Hello\");"];

        $count = new ConstantExpression(5, 0);
        $body = new TextNode('There is 1 pending task', 0);
        $plural = new Node([
            new TextNode('There are ', 0),
            new PrintNode(new NameExpression('count', 0), 0),
            new TextNode(' pending tasks', 0),
        ], [], 0);
        $notes = new TextNode('Notes for translators', 0);
        $node = new TransNode($body, $plural, $count, null, $notes, null, 0);
        $tests[] = [
            $node,
            '// notes: Notes for translators' . "\n" . 'echo strtr(ngettext("There is 1 pending task",'
            . ' "There are %count% pending tasks", abs(5)), array("%count%" => abs(5), ));',
        ];

        return $tests;
    }
}
