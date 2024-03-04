<?php declare(strict_types = 1);

namespace ApiGen\Analyzer\NodeVisitors;

use ApiGen\Analyzer\IdentifierKind;
use ApiGen\Analyzer\NameContextFrame;
use ApiGen\Info\AliasReferenceInfo;
use ApiGen\Info\ClassLikeReferenceInfo;
use ApiGen\Info\ConstantReferenceInfo;
use ApiGen\Info\Expr\ArrayExprInfo;
use ApiGen\Info\Expr\ArrayItemExprInfo;
use ApiGen\Info\Expr\BooleanExprInfo;
use ApiGen\Info\Expr\ClassConstantFetchExprInfo;
use ApiGen\Info\Expr\ConstantFetchExprInfo;
use ApiGen\Info\Expr\FloatExprInfo;
use ApiGen\Info\Expr\IntegerExprInfo;
use ApiGen\Info\Expr\NullExprInfo;
use ApiGen\Info\Expr\StringExprInfo;
use ApiGen\Info\ExprInfo;
use ApiGen\Info\FunctionReferenceInfo;
use ApiGen\Info\MemberReferenceInfo;
use ApiGen\Info\MethodReferenceInfo;
use ApiGen\Info\PropertyReferenceInfo;
use LogicException;
use Nette\Utils\Strings;
use Nette\Utils\Validators;
use PhpParser\NameContext;
use PhpParser\Node;
use PhpParser\Node\Stmt\Use_;
use PhpParser\NodeVisitorAbstract;
use PHPStan\PhpDocParser\Ast\ConstExpr\ConstExprArrayNode;
use PHPStan\PhpDocParser\Ast\ConstExpr\ConstExprFalseNode;
use PHPStan\PhpDocParser\Ast\ConstExpr\ConstExprFloatNode;
use PHPStan\PhpDocParser\Ast\ConstExpr\ConstExprIntegerNode;
use PHPStan\PhpDocParser\Ast\ConstExpr\ConstExprNode;
use PHPStan\PhpDocParser\Ast\ConstExpr\ConstExprNullNode;
use PHPStan\PhpDocParser\Ast\ConstExpr\ConstExprStringNode;
use PHPStan\PhpDocParser\Ast\ConstExpr\ConstExprTrueNode;
use PHPStan\PhpDocParser\Ast\ConstExpr\ConstFetchNode;
use PHPStan\PhpDocParser\Ast\PhpDoc\PhpDocNode;
use PHPStan\PhpDocParser\Ast\PhpDoc\PhpDocTagNode;
use PHPStan\PhpDocParser\Ast\PhpDoc\PhpDocTextNode;
use PHPStan\PhpDocParser\Ast\PhpDoc\TemplateTagValueNode;
use PHPStan\PhpDocParser\Ast\PhpDoc\TypeAliasImportTagValueNode;
use PHPStan\PhpDocParser\Ast\PhpDoc\TypeAliasTagValueNode;
use PHPStan\PhpDocParser\Ast\Type\ArrayShapeItemNode;
use PHPStan\PhpDocParser\Ast\Type\IdentifierTypeNode;
use PHPStan\PhpDocParser\Ast\Type\ObjectShapeItemNode;
use PHPStan\PhpDocParser\Lexer\Lexer;
use PHPStan\PhpDocParser\Parser\PhpDocParser;
use PHPStan\PhpDocParser\Parser\TokenIterator;

use function array_filter;
use function assert;
use function count;
use function explode;
use function get_debug_type;
use function is_array;
use function is_object;
use function property_exists;
use function sprintf;
use function str_contains;
use function strlen;
use function strtolower;
use function substr;


class PhpDocResolver extends NodeVisitorAbstract
{
	protected const NATIVE_KEYWORDS = [
		'array' => true,
		'bool' => true,
		'callable' => true,
		'false' => true,
		'float' => true,
		'int' => true,
		'iterable' => true,
		'mixed' => true,
		'never' => true,
		'null' => true,
		'object' => true,
		'parent' => true,
		'self' => true,
		'static' => true,
		'string' => true,
		'true' => true,
		'void' => true,
	];

	protected const KEYWORDS = self::NATIVE_KEYWORDS + [
		'array-key' => true,
		'associative-array' => true,
		'boolean' => true,
		'callable-object' => true,
		'callable-string' => true,
		'class-string' => true,
		'double' => true,
		'empty' => true,
		'integer' => true,
		'interface-string' => true,
		'key-of' => true,
		'list' => true,
		'literal-string' => true,
		'lowercase-string' => true,
		'max' => true,
		'min' => true,
		'negative-int' => true,
		'never-return' => true,
		'never-returns' => true,
		'no-return' => true,
		'non-empty-array' => true,
		'non-empty-list' => true,
		'non-empty-lowercase-string' => true,
		'non-empty-string' => true,
		'non-falsy-string' => true,
		'non-negative-int' => true,
		'non-positive-int' => true,
		'noreturn' => true,
		'number' => true,
		'numeric' => true,
		'numeric-string' => true,
		'positive-int' => true,
		'resource' => true,
		'scalar' => true,
		'trait-string' => true,
		'truthy-string' => true,
		'value-of' => true,
	];

	protected NameContextFrame $nameContextFrame;


	public function __construct(
		protected Lexer $lexer,
		protected PhpDocParser $parser,
		protected NameContext $nameContext,
	) {
		$this->nameContextFrame = new NameContextFrame(parent: null);
	}


	public function enterNode(Node $node): null|int|Node
	{
		$doc = $node->getDocComment();

		if ($doc !== null) {
			$tokens = $this->lexer->tokenize($doc->getText());
			$phpDoc = $this->parser->parse(new TokenIterator($tokens));

			if ($node instanceof Node\Stmt\ClassLike) {
				assert($node->namespacedName !== null);
				$scope = new ClassLikeReferenceInfo($node->namespacedName->toString());
				$this->nameContextFrame = $this->resolveNameContext($phpDoc, $this->nameContextFrame, $scope);

			} elseif ($node instanceof Node\FunctionLike) {
				$scope = $this->nameContextFrame->scope;
				$this->nameContextFrame = $this->resolveNameContext($phpDoc, $this->nameContextFrame, $scope);
			}

			$node->setAttribute('phpDoc', $this->resolvePhpDoc($phpDoc));

		} elseif ($node instanceof Node\Stmt\ClassLike || $node instanceof Node\FunctionLike) {
			$this->nameContextFrame = new NameContextFrame($this->nameContextFrame);
		}

		return null;
	}


	public function leaveNode(Node $node): null|int|Node|array
	{
		if ($node instanceof Node\Stmt\ClassLike || $node instanceof Node\FunctionLike) {
			if ($this->nameContextFrame->parent === null) {
				throw new \LogicException('Name context stack is empty.');

			} else {
				$this->nameContextFrame = $this->nameContextFrame->parent;
			}
		}

		return null;
	}


	protected function resolveNameContext(PhpDocNode $doc, NameContextFrame $parent, ?ClassLikeReferenceInfo $scope): NameContextFrame
	{
		$frame = new NameContextFrame($parent);

		foreach ($doc->children as $child) {
			if ($child instanceof PhpDocTagNode) {
				if ($child->value instanceof TypeAliasTagValueNode) {
					assert($scope !== null);
					$lower = strtolower($child->value->alias);
					$frame->aliases[$lower] = new AliasReferenceInfo($scope, $child->value->alias);

				} elseif ($child->value instanceof TypeAliasImportTagValueNode) {
					$classLike = new ClassLikeReferenceInfo($this->resolveClassLikeIdentifier($child->value->importedFrom->name));
					$lower = strtolower($child->value->importedAs ?? $child->value->importedAlias);
					$frame->aliases[$lower] = new AliasReferenceInfo($classLike, $child->value->importedAlias);

				} elseif ($child->value instanceof TemplateTagValueNode) {
					$lower = strtolower($child->value->name);
					$frame->genericParameters[$lower] = true;
				}
			}
		}

		return $frame;
	}


	protected function resolvePhpDoc(PhpDocNode $phpDoc): PhpDocNode
	{
		$newChildren = [];

		foreach ($phpDoc->children as $child) {
			if ($child instanceof PhpDocTagNode) {
				$this->resolvePhpDocTag($child);
				$newChildren[] = $child;

			} elseif ($child instanceof PhpDocTextNode) {
				foreach ($this->resolvePhpDocTextNode($child->text) as $newChild) {
					$newChildren[] = $newChild;
				}
			}
		}

		return new PhpDocNode($newChildren);
	}


	protected function resolvePhpDocTag(PhpDocTagNode $tag): void
	{
		$stack = [$tag];
		$index = 1;

		while ($index > 0) {
			$value = $stack[--$index];

			if ($value instanceof IdentifierTypeNode) {
				$this->resolveIdentifier($value);

			} elseif ($value instanceof ConstExprNode) {
				$value->setAttribute('info', $this->resolveConstExpr($value));

			} elseif ($value instanceof ArrayShapeItemNode || $value instanceof ObjectShapeItemNode) {
				$stack[$index++] = $value->valueType; // intentionally not pushing $value->keyName

			} else {
				foreach ((array) $value as $item) {
					if (is_array($item) || is_object($item)) {
						$stack[$index++] = $item;
					}
				}
			}
		}

		if (property_exists($tag->value, 'description')) {
			$tag->value->setAttribute('description', $this->resolvePhpDocTextNode($tag->value->description));
		}
	}


	/**
	 * @return PhpDocTextNode[] indexed by []
	 */
	public function resolvePhpDocTextNode(string $text): array
	{
		$matches = Strings::matchAll($text, '#\{(@(?:[a-z][a-z0-9-\\\\]+:)?[a-z][a-z0-9-\\\\]*+)(?:[ \t]++([^}]++))?\}#', captureOffset: true);

		$nodes = [];
		$offset = 0;

		foreach ($matches as $match) {
			$matchText = $match[0][0];
			$matchOffset = $match[0][1];
			$tagName = $match[1][0];
			$tagValue = $match[2][0] ?? '';

			$nodes[] = new PhpDocTextNode(substr($text, $offset, $matchOffset - $offset));
			$nodes[] = $this->resolveInlineTag($tagName, $tagValue) ?? new PhpDocTextNode($matchText);
			$offset = $matchOffset + strlen($matchText);
		}

		$nodes[] = new PhpDocTextNode(substr($text, $offset));
		return array_filter($nodes, static fn(PhpDocTextNode $node): bool => $node->text !== '');
	}


	protected function resolveInlineTag(string $tagName, string $tagValue): ?PhpDocTextNode
	{
		if ($tagName === '@link' || $tagName === '@see') {
			$parts = explode(' ', $tagValue, 2);
			$node = new PhpDocTextNode($parts[1] ?? $parts[0]);
			$references = $this->resolveLinkTarget($parts[0]);

			if (count($references) > 0) {
				$node->setAttribute('targets', $references);
			}

			return $node;
		}

		return null;
	}


	/**
	 * @return list<ClassLikeReferenceInfo|MemberReferenceInfo|FunctionReferenceInfo|string>
	 */
	protected function resolveLinkTarget(string $target): array
	{
		$identifier = '[a-zA-Z_\\x7f-\\xff][a-zA-Z0-9_\\x7f-\\xff]*+';
		$qualifiedIdentifier = "\\\\?+{$identifier}(?:\\\\{$identifier})*+";
		$references = [];

		if (($match = Strings::match($target, "#^{$qualifiedIdentifier}#")) !== null) {
			$classLike = new ClassLikeReferenceInfo($this->resolveClassLikeIdentifier($match[0]));
			$offset = strlen($match[0]);

			if ($offset === strlen($target)) {
				$references[] = $classLike;

				foreach ($this->resolveFunctionLinkTarget($match[0]) as $functionReference) {
					$references[] = $functionReference;
				}

				if (!str_contains($target, '\\')) {
					$classLike = new ClassLikeReferenceInfo('self');
					$references[] = new ConstantReferenceInfo($classLike, $target);
					$references[] = new MethodReferenceInfo($classLike, $target);
				}

			} elseif (($match = Strings::match($target, "#::($identifier)\\(\\)$#A", offset: $offset)) !== null) {
				$references[] = new MethodReferenceInfo($classLike, $match[1]);

			} elseif (($match = Strings::match($target, "#::($identifier)$#A", offset: $offset)) !== null) {
				$references[] = new ConstantReferenceInfo($classLike, $match[1]);
				$references[] = new MethodReferenceInfo($classLike, $match[1]);

			} elseif (($match = Strings::match($target, "#::\\\$($identifier)$#A", offset: $offset)) !== null) {
				$references[] = new PropertyReferenceInfo($classLike, $match[1]);

			} elseif (Strings::match($target, "#\\(\\)$#A", offset: $offset) !== null) {
				$functionName = substr($target, 0, -2);
				foreach ($this->resolveFunctionLinkTarget($functionName) as $functionReference) {
					$references[] = $functionReference;
				}

				if (!str_contains($functionName, '\\')) {
					$classLike = new ClassLikeReferenceInfo('self');
					$references[] = new MethodReferenceInfo($classLike, $functionName);
				}

			} elseif (Validators::isUrl($target)) {
				$references[] = $target;
			}

		} elseif (($match = Strings::match($target, "#^\\\$($identifier)$#")) !== null) {
			$classLike = new ClassLikeReferenceInfo('self');
			$references[] = new PropertyReferenceInfo($classLike, $match[1]);
		}

		return $references;
	}


	/**
	 * @return FunctionReferenceInfo[] indexed by []
	 */
	protected function resolveFunctionLinkTarget(string $target): array
	{
		$resolvedFunctionIdentifier = $this->resolveFunctionIdentifier($target);

		if ($resolvedFunctionIdentifier !== null) {
			return [
				new FunctionReferenceInfo($resolvedFunctionIdentifier),
			];

		} elseif (($namespace = $this->nameContext->getNamespace()?->toString()) !== null) {
			return [
				new FunctionReferenceInfo("{$namespace}\\{$target}"),
				new FunctionReferenceInfo($target),
			];

		} else {
			throw new LogicException("Unable to resolve function {$target}");
		}
	}


	protected function resolveIdentifier(IdentifierTypeNode $identifier): void
	{
		$lower = strtolower($identifier->name);

		if (isset(self::KEYWORDS[$identifier->name]) || isset(self::NATIVE_KEYWORDS[$lower]) || str_contains($lower, '-')) {
			$identifier->setAttribute('kind', IdentifierKind::Keyword);

		} elseif (isset($this->nameContextFrame->genericParameters[$lower])) {
			$identifier->setAttribute('kind', IdentifierKind::Generic);

		} elseif (isset($this->nameContextFrame->aliases[$lower])) {
			$identifier->setAttribute('kind', IdentifierKind::Alias);
			$identifier->setAttribute('aliasReference', $this->nameContextFrame->aliases[$lower]);

		} else {
			$classLikeReference = new ClassLikeReferenceInfo($this->resolveClassLikeIdentifier($identifier->name));
			$identifier->setAttribute('kind', IdentifierKind::ClassLike);
			$identifier->setAttribute('classLikeReference', $classLikeReference);
		}
	}


	protected function resolveClassLikeIdentifier(string $identifier): string
	{
		if ($identifier[0] === '\\') {
			return substr($identifier, 1);

		} else {
			return $this->nameContext->getResolvedClassName(new Node\Name($identifier))->toString();
		}
	}


	protected function resolveFunctionIdentifier(string $identifier): ?string
	{
		if ($identifier[0] === '\\') {
			return substr($identifier, 1);

		} else {
			return $this->nameContext->getResolvedName(new Node\Name($identifier), Use_::TYPE_FUNCTION)?->toString();
		}
	}


	protected function resolveConstExpr(ConstExprNode $expr): ExprInfo
	{
		if ($expr instanceof ConstExprTrueNode) {
			return new BooleanExprInfo(true);

		} elseif ($expr instanceof ConstExprFalseNode) {
			return new BooleanExprInfo(false);

		} elseif ($expr instanceof ConstExprNullNode) {
			return new NullExprInfo();

		} elseif ($expr instanceof ConstExprIntegerNode) {
			$node = Node\Scalar\LNumber::fromString($expr->value);
			return new IntegerExprInfo($node->value, $node->getAttribute('kind'), $expr->value);

		} elseif ($expr instanceof ConstExprFloatNode) {
			return new FloatExprInfo(Node\Scalar\DNumber::parse($expr->value), $expr->value);

		} elseif ($expr instanceof ConstExprStringNode) {
			return new StringExprInfo($expr->value, raw: null);

		} elseif ($expr instanceof ConstExprArrayNode) {
			$items = [];

			foreach ($expr->items as $item) {
				$items[] = new ArrayItemExprInfo(
					$item->key ? $this->resolveConstExpr($item->key) : null,
					$this->resolveConstExpr($item->value),
				);
			}

			return new ArrayExprInfo($items);

		} elseif ($expr instanceof ConstFetchNode) {
			if ($expr->className === '') {
				return new ConstantFetchExprInfo($expr->name);

			} else {
				return new ClassConstantFetchExprInfo(new ClassLikeReferenceInfo($this->resolveClassLikeIdentifier($expr->className)), $expr->name);
			}

		} else {
			throw new \LogicException(sprintf('Unsupported const expr node %s used in PHPDoc', get_debug_type($expr)));
		}
	}
}
