<?php declare(strict_types = 1);

namespace ApiGen\Renderer\Latte;

use Generator;
use Latte;
use Latte\Compiler\Node;
use Latte\Compiler\Nodes\AreaNode;
use Latte\Compiler\Nodes\TextNode;
use Latte\Compiler\NodeTraverser;
use Latte\Compiler\PrintContext;
use Latte\Compiler\Tag;
use Latte\Compiler\TemplateParser;
use Nette\Utils\Strings;

use function assert;


class LattePreNode extends Latte\Compiler\Nodes\StatementNode
{
	public function __construct(
		public AreaNode $content,
	) {
	}


	/**
	 * @return Generator<int, ?array, array{AreaNode, ?Tag}, self>
	 */
	public static function create(Tag $tag, TemplateParser $parser): Generator
	{
		[$content] = yield;
		assert($content instanceof AreaNode);

		$transformed = (new NodeTraverser)->traverse($content, self::removeWhitespace(...));
		assert($transformed instanceof AreaNode);

		return new self($transformed);
	}


	public function print(PrintContext $context): string
	{
		return $this->content->print($context);
	}


	public function &getIterator(): Generator
	{
		yield $this->content;
	}


	protected static function removeWhitespace(Node $node): Node
	{
		return $node instanceof TextNode ? new TextNode(Strings::replace($node->content, '#[\n\t]++#')) : $node;
	}
}
