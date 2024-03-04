<?php declare(strict_types = 1);

namespace ApiGen\Analyzer;

use ApiGen\Info\AliasInfo;
use ApiGen\Info\ClassInfo;
use ApiGen\Info\ClassLikeInfo;
use ApiGen\Info\ClassLikeReferenceInfo;
use ApiGen\Info\ConstantInfo;
use ApiGen\Info\EnumCaseInfo;
use ApiGen\Info\EnumInfo;
use ApiGen\Info\ErrorInfo;
use ApiGen\Info\ErrorKind;
use ApiGen\Info\Expr\ArgExprInfo;
use ApiGen\Info\Expr\ArrayExprInfo;
use ApiGen\Info\Expr\ArrayItemExprInfo;
use ApiGen\Info\Expr\BinaryOpExprInfo;
use ApiGen\Info\Expr\BooleanExprInfo;
use ApiGen\Info\Expr\ClassConstantFetchExprInfo;
use ApiGen\Info\Expr\ConstantFetchExprInfo;
use ApiGen\Info\Expr\DimFetchExprInfo;
use ApiGen\Info\Expr\FloatExprInfo;
use ApiGen\Info\Expr\IntegerExprInfo;
use ApiGen\Info\Expr\NewExprInfo;
use ApiGen\Info\Expr\NullExprInfo;
use ApiGen\Info\Expr\NullSafePropertyFetchExprInfo;
use ApiGen\Info\Expr\PropertyFetchExprInfo;
use ApiGen\Info\Expr\StringExprInfo;
use ApiGen\Info\Expr\TernaryExprInfo;
use ApiGen\Info\Expr\UnaryOpExprInfo;
use ApiGen\Info\ExprInfo;
use ApiGen\Info\FunctionInfo;
use ApiGen\Info\GenericParameterInfo;
use ApiGen\Info\GenericParameterVariance;
use ApiGen\Info\InterfaceInfo;
use ApiGen\Info\MemberInfo;
use ApiGen\Info\MethodInfo;
use ApiGen\Info\NameInfo;
use ApiGen\Info\ParameterInfo;
use ApiGen\Info\PropertyInfo;
use ApiGen\Info\TraitInfo;
use ApiGen\Task\Task;
use ApiGen\Task\TaskHandler;
use BackedEnum;
use Iterator;
use Nette\Utils\FileSystem;
use PhpParser\Node;
use PhpParser\Node\ComplexType;
use PhpParser\Node\Identifier;
use PhpParser\Node\IntersectionType;
use PhpParser\Node\Name;
use PhpParser\Node\NullableType;
use PhpParser\Node\UnionType;
use PhpParser\NodeTraverserInterface;
use PhpParser\Parser;
use PHPStan\PhpDocParser\Ast\PhpDoc\ExtendsTagValueNode;
use PHPStan\PhpDocParser\Ast\PhpDoc\ImplementsTagValueNode;
use PHPStan\PhpDocParser\Ast\PhpDoc\InvalidTagValueNode;
use PHPStan\PhpDocParser\Ast\PhpDoc\MethodTagValueNode;
use PHPStan\PhpDocParser\Ast\PhpDoc\MixinTagValueNode;
use PHPStan\PhpDocParser\Ast\PhpDoc\ParamTagValueNode;
use PHPStan\PhpDocParser\Ast\PhpDoc\PhpDocNode;
use PHPStan\PhpDocParser\Ast\PhpDoc\PhpDocTagNode;
use PHPStan\PhpDocParser\Ast\PhpDoc\PhpDocTagValueNode;
use PHPStan\PhpDocParser\Ast\PhpDoc\PhpDocTextNode;
use PHPStan\PhpDocParser\Ast\PhpDoc\PropertyTagValueNode;
use PHPStan\PhpDocParser\Ast\PhpDoc\ReturnTagValueNode;
use PHPStan\PhpDocParser\Ast\PhpDoc\TemplateTagValueNode;
use PHPStan\PhpDocParser\Ast\PhpDoc\TypeAliasTagValueNode;
use PHPStan\PhpDocParser\Ast\PhpDoc\UsesTagValueNode;
use PHPStan\PhpDocParser\Ast\PhpDoc\VarTagValueNode;
use PHPStan\PhpDocParser\Ast\Type\IdentifierTypeNode;
use PHPStan\PhpDocParser\Ast\Type\IntersectionTypeNode;
use PHPStan\PhpDocParser\Ast\Type\NullableTypeNode;
use PHPStan\PhpDocParser\Ast\Type\TypeNode;
use PHPStan\PhpDocParser\Ast\Type\UnionTypeNode;
use UnitEnum;

use function array_map;
use function assert;
use function get_debug_type;
use function is_array;
use function is_object;
use function is_scalar;
use function is_string;
use function iterator_to_array;
use function mb_check_encoding;
use function sprintf;
use function str_ends_with;
use function strtolower;
use function substr;


/**
 * @implements TaskHandler<AnalyzeTask, array<ClassLikeInfo | FunctionInfo | ClassLikeReferenceInfo | ErrorInfo>>
 */
class AnalyzeTaskHandler implements TaskHandler
{
	/**
	 * @param  null $context
	 */
	public function __construct(
		protected Parser $parser,
		protected NodeTraverserInterface $traverser,
		protected Filter $filter,
		protected mixed $context = null,
	) {
	}


	/**
	 * @param  AnalyzeTask $task
	 */
	public function handle(Task $task): array
	{
		try {
			$content = FileSystem::read($task->sourceFile);

			if (!mb_check_encoding($content, 'UTF-8')) {
				$error = new ErrorInfo(ErrorKind::InvalidEncoding, "File {$task->sourceFile} is not UTF-8 encoded");
				return [$error];
			}

			$ast = $this->parser->parse($content) ?? throw new \LogicException();
			$ast = $this->traverser->traverse($ast);
			return iterator_to_array($this->processNodes($task, $ast), preserve_keys: false);

		} catch (\PhpParser\Error $e) {
			$error = new ErrorInfo(ErrorKind::SyntaxError, "Parse error in file {$task->sourceFile}:\n{$e->getMessage()}");
			return [$error];

		} catch (\Throwable $e) {
			$ex = new \LogicException("Failed to analyze file $task->sourceFile", 0, $e);
			$error = new ErrorInfo(ErrorKind::InternalError, (string) $ex);
			return [$error];
		}
	}


	/**
	 * @param  Node[] $nodes indexed by []
	 * @return Iterator<ClassLikeInfo | FunctionInfo | ClassLikeReferenceInfo>
	 */
	protected function processNodes(AnalyzeTask $task, array $nodes): Iterator
	{
		foreach ($nodes as $node) {
			if ($node instanceof Node\Stmt\ClassLike && $node->name !== null) {
				try {
					$task->primary = $task->primary && $this->filter->filterClassLikeNode($node);
					$classLike = $this->processClassLike($task, $node);
					yield $classLike;
					yield from $this->extractDependencies($classLike);

				} catch (\Throwable $e) {
					throw new \LogicException("Failed to analyze class-like $node->namespacedName", 0, $e);
				}

			} elseif ($node instanceof Node\Stmt\Function_) {
				try {
					$functionInfo = $this->processFunction($task, $node);

					if ($functionInfo !== null) {
						yield $functionInfo;
						yield from $this->extractDependencies($functionInfo);
					}

				} catch (\Throwable $e) {
					throw new \LogicException("Failed to analyze function $node->namespacedName", 0, $e);
				}

			} elseif ($node instanceof Node\Stmt) { // TODO: constants, class aliases
				foreach ($node->getSubNodeNames() as $name) {
					$subNode = $node->$name;

					if (is_array($subNode)) {
						yield from $this->processNodes($task, $subNode);

					} elseif ($subNode instanceof Node) {
						yield from $this->processNodes($task, [$subNode]);
					}
				}
			}
		}
	}


	protected function processClassLike(AnalyzeTask $task, Node\Stmt\ClassLike $node): ClassLikeInfo
	{
		$extendsTagNames = ['extends', 'template-extends', 'phpstan-extends'];
		$implementsTagNames = ['implements', 'template-implements', 'phpstan-implements'];
		$useTagNames = ['use', 'template-use', 'phpstan-use'];

		assert($node->namespacedName !== null);
		$name = new NameInfo($node->namespacedName->toString());

		$classDoc = $this->extractPhpDoc($node);
		$tags = $this->extractTags($classDoc);

		if ($task->primary && !$this->filter->filterClassLikeTags($tags)) {
			$task->primary = false;
		}

		if ($node instanceof Node\Stmt\Class_) {
			$info = new ClassInfo($name, $task->primary);
			$info->abstract = $node->isAbstract();
			$info->final = $node->isFinal();
			$info->readOnly = $node->isReadonly();
			$info->extends = $node->extends ? $this->processName($node->extends, $tags, $extendsTagNames) : null;
			$info->implements = $this->processNameList($node->implements, $tags, $implementsTagNames);

			$info->aliases = $this->extractAliases($classDoc, $node->getDocComment()?->getStartLine(), $node->getDocComment()?->getEndLine());
			unset($tags['phpstan-type'], $tags['phpstan-import-type']);

			foreach ($node->getTraitUses() as $traitUse) { // TODO: trait adaptations
				$info->uses += $this->processNameList($traitUse->traits, $tags, $useTagNames);
			}

		} elseif ($node instanceof Node\Stmt\Interface_) {
			$info = new InterfaceInfo($name, $task->primary);
			$info->extends = $this->processNameList($node->extends, $tags, $extendsTagNames);

		} elseif ($node instanceof Node\Stmt\Trait_) {
			$info = new TraitInfo($name, $task->primary);

		} elseif ($node instanceof Node\Stmt\Enum_) {
			$autoImplement = new ClassLikeReferenceInfo($node->scalarType ? BackedEnum::class : UnitEnum::class);

			$info = new EnumInfo($name, $task->primary);
			$info->scalarType = $node->scalarType?->name;
			$info->implements = $this->processNameList($node->implements, $tags, $implementsTagNames) + [$autoImplement->fullLower => $autoImplement];

			foreach ($node->getTraitUses() as $traitUse) {
				$info->uses += $this->processNameList($traitUse->traits, $tags, $useTagNames);
			}

		} else {
			throw new \LogicException(sprintf('Unsupported ClassLike node %s', get_debug_type($node)));
		}

		$info->genericParameters = $this->extractGenericParameters($classDoc);
		$info->description = $this->extractMultiLineDescription($classDoc);
		$info->tags = $tags;
		$info->file = $task->sourceFile;
		$info->startLine = $node->getStartLine();
		$info->endLine = $node->getEndLine();

		$info->mixins = $this->processMixinTags($tags['mixin'] ?? []);

		foreach ($this->extractMembers($info->tags, $node) as $member) {
			if (!$this->filter->filterMemberInfo($info, $member)) {
				continue;

			} elseif ($member instanceof ConstantInfo) {
				$info->constants[$member->name] = $member;

			} elseif ($member instanceof PropertyInfo) {
				$info->properties[$member->name] = $member;

			} elseif ($member instanceof MethodInfo) {
				$info->methods[$member->nameLower] = $member;

			} elseif ($member instanceof EnumCaseInfo) {
				assert($info instanceof EnumInfo);
				$info->cases[$member->name] = $member;

			} else {
				throw new \LogicException(sprintf('Unexpected member type %s', get_debug_type($member)));
			}
		}

		unset($info->tags['mixin']);
		unset($info->tags['property'], $info->tags['property-read'], $info->tags['property-write']);
		unset($info->tags['method']);

		if ($info->primary && !$this->filter->filterClassLikeInfo($info)) {
			$info->primary = false;
		}

		return $info;
	}


	/**
	 * @param  PhpDocTagValueNode[][] $tags indexed by [tagName][]
	 * @return iterable<MemberInfo>
	 */
	protected function extractMembers(array $tags, Node\Stmt\ClassLike $node): iterable
	{
		yield from $this->extractMembersFromBody($node);
		yield from $this->extractMembersFromTags($tags);
	}


	/**
	 * @return iterable<MemberInfo>
	 */
	protected function extractMembersFromBody(Node\Stmt\ClassLike $node): iterable
	{
		foreach ($node->stmts as $member) {
			$memberDoc = $this->extractPhpDoc($member);
			$description = $this->extractMultiLineDescription($memberDoc);
			$tags = $this->extractTags($memberDoc);

			if (!$this->filter->filterMemberTags($tags)) {
				continue;
			}

			if ($member instanceof Node\Stmt\ClassConst) {
				if (!$this->filter->filterConstantNode($member)) {
					continue;
				}

				foreach ($member->consts as $constant) {
					$memberInfo = new ConstantInfo($constant->name->name, $this->processExpr($constant->value));

					$memberInfo->description = $description;
					$memberInfo->tags = $tags;

					$memberInfo->startLine = $member->getComments() ? $member->getComments()[0]->getStartLine() : $member->getStartLine();
					$memberInfo->endLine = $member->getEndLine();

					$memberInfo->public = $member->isPublic();
					$memberInfo->protected = $member->isProtected();
					$memberInfo->private = $member->isPrivate();
					$memberInfo->final = $member->isFinal();

					yield $memberInfo;
				}

			} elseif ($member instanceof Node\Stmt\Property) {
				if (!$this->filter->filterPropertyNode($member)) {
					continue;
				}

				$varTag = isset($tags['var'][0]) && $tags['var'][0] instanceof VarTagValueNode ? $tags['var'][0] : null;
				unset($tags['var']);

				foreach ($member->props as $property) {
					$memberInfo = new PropertyInfo($property->name->name);

					$memberInfo->description = $this->extractSingleLineDescription($varTag);
					$memberInfo->tags = $tags;

					$memberInfo->startLine = $member->getComments() ? $member->getComments()[0]->getStartLine() : $member->getStartLine();
					$memberInfo->endLine = $member->getEndLine();

					$memberInfo->public = $member->isPublic();
					$memberInfo->protected = $member->isProtected();
					$memberInfo->private = $member->isPrivate();
					$memberInfo->static = $member->isStatic();
					$memberInfo->readOnly = $member->isReadonly();

					$memberInfo->type = $varTag ? $varTag->type : $this->processTypeOrNull($member->type);
					$memberInfo->default = $this->processExprOrNull($property->default);

					yield $memberInfo;
				}

			} elseif ($member instanceof Node\Stmt\ClassMethod) {
				if (!$this->filter->filterMethodNode($member)) {
					continue;
				}

				/** @var ?ReturnTagValueNode $returnTag */
				$returnTag = isset($tags['return'][0]) && $tags['return'][0] instanceof ReturnTagValueNode ? $tags['return'][0] : null;
				unset($tags['param'], $tags['return']);

				$memberInfo = new MethodInfo($member->name->name);

				$memberInfo->description = $description;
				$memberInfo->tags = $tags;

				$memberInfo->genericParameters = $this->extractGenericParameters($memberDoc);
				$memberInfo->parameters = $this->processParameters($this->extractParamTagValues($memberDoc), $member->params);
				$memberInfo->returnType = $returnTag ? $returnTag->type : $this->processTypeOrNull($member->returnType);
				$memberInfo->returnDescription = $this->extractSingleLineDescription($returnTag);
				$memberInfo->byRef = $member->byRef;

				$memberInfo->startLine = $member->getComments() ? $member->getComments()[0]->getStartLine() : $member->getStartLine();
				$memberInfo->endLine = $member->getEndLine();

				$memberInfo->public = $member->isPublic();
				$memberInfo->protected = $member->isProtected();
				$memberInfo->private = $member->isPrivate();

				$memberInfo->static = $member->isStatic();
				$memberInfo->abstract = $member->isAbstract();
				$memberInfo->final = $member->isFinal();

				yield $memberInfo;

				if ($member->name->toLowerString() === '__construct') {
					foreach ($member->params as $param) {
						if ($param->flags === 0 || !$this->filter->filterPromotedPropertyNode($param)) {
							continue;
						}

						assert($param->var instanceof Node\Expr\Variable);
						assert(is_string($param->var->name));
						$propertyInfo = new PropertyInfo($param->var->name);

						$propertyInfo->description = $memberInfo->parameters[$propertyInfo->name]->description;

						$propertyInfo->startLine = $param->getStartLine();
						$propertyInfo->endLine = $param->getEndLine();

						$propertyInfo->public = (bool) ($param->flags & Node\Stmt\Class_::MODIFIER_PUBLIC);
						$propertyInfo->protected = (bool) ($param->flags & Node\Stmt\Class_::MODIFIER_PROTECTED);
						$propertyInfo->private = (bool) ($param->flags & Node\Stmt\Class_::MODIFIER_PRIVATE);

						$propertyInfo->readOnly = (bool) ($param->flags & Node\Stmt\Class_::MODIFIER_READONLY);
						$propertyInfo->type = $memberInfo->parameters[$propertyInfo->name]->type;

						yield $propertyInfo;
					}
				}

			} elseif ($member instanceof Node\Stmt\EnumCase) {
				if (!$this->filter->filterEnumCaseNode($member)) {
					continue;
				}

				$memberInfo = new EnumCaseInfo($member->name->name, $this->processExprOrNull($member->expr));

				$memberInfo->description = $description;
				$memberInfo->tags = $tags;

				$memberInfo->startLine = $member->getComments() ? $member->getComments()[0]->getStartLine() : $member->getStartLine();
				$memberInfo->endLine = $member->getEndLine();

				yield $memberInfo;
			}
		}
	}


	/**
	 * @param  PhpDocTagValueNode[][] $tags indexed by [tagName][]
	 * @return iterable<MemberInfo>
	 */
	protected function extractMembersFromTags(array $tags): iterable
	{
		$propertyTags = [
			'property' => [false, false],
			'property-read' => [true, false],
			'property-write' => [false, true],
		];

		foreach ($propertyTags as $tag => [$readOnly, $writeOnly]) {
			/** @var PropertyTagValueNode $value */
			foreach ($tags[$tag] ?? [] as $value) {
				$propertyInfo = new PropertyInfo(substr($value->propertyName, 1));
				$propertyInfo->magic = true;
				$propertyInfo->public = true;
				$propertyInfo->type = $value->type;
				$propertyInfo->description = $this->extractSingleLineDescription($value);
				$propertyInfo->readOnly = $readOnly;
				$propertyInfo->writeOnly = $writeOnly;

				yield $propertyInfo;
			}
		}

		/** @var MethodTagValueNode $value */
		foreach ($tags['method'] ?? [] as $value) {
			$methodInfo = new MethodInfo($value->methodName);
			$methodInfo->magic = true;
			$methodInfo->public = true;
			$methodInfo->static = $value->isStatic;
			$methodInfo->returnType = $value->returnType;
			$methodInfo->description = $this->extractSingleLineDescription($value);

			foreach ($value->parameters as $position => $parameter) {
				$parameterInfo = new ParameterInfo(substr($parameter->parameterName, 1), $position);
				$parameterInfo->type = $parameter->type;
				$parameterInfo->byRef = $parameter->isReference;
				$parameterInfo->variadic = $parameter->isVariadic;
				$parameterInfo->default = $parameter->defaultValue?->getAttribute('info');

				$methodInfo->parameters[$parameterInfo->name] = $parameterInfo;
			}

			yield $methodInfo;
		}
	}


	protected function processFunction(AnalyzeTask $task, Node\Stmt\Function_ $node): ?FunctionInfo
	{
		if (!$this->filter->filterFunctionNode($node)) {
			return null;
		}

		$phpDoc = $this->extractPhpDoc($node);
		$tags = $this->extractTags($phpDoc);

		if (!$this->filter->filterFunctionTags($tags)) {
			return null;
		}

		assert($node->namespacedName !== null);
		$name = new NameInfo($node->namespacedName->toString());
		$info = new FunctionInfo($name, $task->primary);

		$info->description = $this->extractMultiLineDescription($phpDoc);
		$info->tags = $tags;
		$info->file = $task->sourceFile;
		$info->startLine = $node->getStartLine();
		$info->endLine = $node->getEndLine();

		/** @var ?ReturnTagValueNode $returnTag */
		$returnTag = isset($tags['return'][0]) && $tags['return'][0] instanceof ReturnTagValueNode ? $tags['return'][0] : null;
		unset($tags['param'], $tags['return']);

		$info->genericParameters = $this->extractGenericParameters($phpDoc);
		$info->parameters = $this->processParameters($this->extractParamTagValues($phpDoc), $node->params);
		$info->returnType = $returnTag ? $returnTag->type : $this->processTypeOrNull($node->returnType);
		$info->returnDescription = $this->extractSingleLineDescription($returnTag);
		$info->byRef = $node->byRef;

		if (!$this->filter->filterFunctionInfo($info)) {
			return null;
		}

		return $info;
	}


	/**
	 * @param  ParamTagValueNode[] $paramTags  indexed by [parameterName]
	 * @param  Node\Param[]        $parameters indexed by []
	 * @return ParameterInfo[]
	 */
	protected function processParameters(array $paramTags, array $parameters): array
	{
		$parameterInfos = [];
		foreach ($parameters as $position => $parameter) {
			assert($parameter->var instanceof Node\Expr\Variable);
			assert(is_scalar($parameter->var->name));

			$paramTag = $paramTags["\${$parameter->var->name}"] ?? null;
			$parameterInfo = new ParameterInfo($parameter->var->name, $position);
			$parameterInfo->description = $this->extractSingleLineDescription($paramTag);
			$parameterInfo->type = $paramTag ? $paramTag->type : $this->processTypeOrNull($parameter->type);
			$parameterInfo->byRef = $parameter->byRef;
			$parameterInfo->variadic = $parameter->variadic || ($paramTag && $paramTag->isVariadic);
			$parameterInfo->default = $this->processExprOrNull($parameter->default);

			$parameterInfos[$parameter->var->name] = $parameterInfo;
		}

		return $parameterInfos;
	}


	/**
	 * @param PhpDocTagValueNode[][] $tagValues indexed by [tagName][]
	 * @param string[]               $tagNames  indexed by []
	 */
	protected function processName(Node\Name $name, array $tagValues = [], array $tagNames = []): ClassLikeReferenceInfo
	{
		$refInfo = new ClassLikeReferenceInfo($name->toString());

		foreach ($tagNames as $tagName) {
			foreach ($tagValues[$tagName] ?? [] as $tagValue) {
				assert($tagValue instanceof ExtendsTagValueNode || $tagValue instanceof ImplementsTagValueNode || $tagValue instanceof UsesTagValueNode);

				$kind = $tagValue->type->type->getAttribute('kind');
				assert($kind instanceof IdentifierKind);

				if ($kind === IdentifierKind::ClassLike) {
					$refInfo = $tagValue->type->type->getAttribute('classLikeReference');
					assert($refInfo instanceof ClassLikeReferenceInfo);

					$refInfo->genericArgs = $tagValue->type->genericTypes;
				}
			}
		}

		return $refInfo;
	}


	/**
	 * @param  Node\Name[]            $names     indexed by []
	 * @param  PhpDocTagValueNode[][] $tagValues indexed by [tagName][]
	 * @param  string[]               $tagNames  indexed by []
	 * @return ClassLikeReferenceInfo[] indexed by [classLikeName]
	 */
	protected function processNameList(array $names, array $tagValues = [], array $tagNames = []): array
	{
		$nameMap = [];

		foreach ($names as $name) {
			$nameInfo = new ClassLikeReferenceInfo($name->toString());
			$nameMap[$nameInfo->fullLower] = $nameInfo;
		}

		foreach ($tagNames as $tagName) {
			foreach ($tagValues[$tagName] ?? [] as $tagValue) {
				assert($tagValue instanceof ExtendsTagValueNode || $tagValue instanceof ImplementsTagValueNode || $tagValue instanceof UsesTagValueNode);

				$kind = $tagValue->type->type->getAttribute('kind');
				assert($kind instanceof IdentifierKind);

				if ($kind === IdentifierKind::ClassLike) {
					$refInfo = $tagValue->type->type->getAttribute('classLikeReference');
					assert($refInfo instanceof ClassLikeReferenceInfo);

					$refInfo->genericArgs = $tagValue->type->genericTypes;
					$nameMap[$refInfo->fullLower] = $refInfo;
				}
			}
		}

		return $nameMap;
	}


	/**
	 * @param  PhpDocTagValueNode[] $values indexed by []
	 * @return ClassLikeReferenceInfo[] indexed by [classLikeName]
	 */
	protected function processMixinTags(array $values): array
	{
		$nameMap = [];

		foreach ($values as $value) {
			if ($value instanceof MixinTagValueNode && $value->type instanceof IdentifierTypeNode) {
				$kind = $value->type->getAttribute('kind');
				assert($kind instanceof IdentifierKind);

				if ($kind === IdentifierKind::ClassLike) {
					$refInfo = $value->type->getAttribute('classLikeReference');
					assert($refInfo instanceof ClassLikeReferenceInfo);

					$nameMap[$refInfo->fullLower] = $refInfo;
				}
			}
		}

		return $nameMap;
	}


	protected function processTypeOrNull(Identifier|Name|ComplexType|null $node): ?TypeNode
	{
		return $node ? $this->processType($node) : null;
	}


	protected function processType(Identifier|Name|ComplexType $node): TypeNode
	{
		if ($node instanceof ComplexType) {
			if ($node instanceof NullableType) {
				return new NullableTypeNode($this->processType($node->type));

			} elseif ($node instanceof UnionType) {
				return new UnionTypeNode(array_map($this->processType(...), $node->types));

			} elseif ($node instanceof IntersectionType) {
				return new IntersectionTypeNode(array_map($this->processType(...), $node->types));

			} else {
				throw new \LogicException(sprintf('Unsupported complex type %s', get_debug_type($node)));
			}

		} elseif ($node instanceof Name && !$node->isSpecialClassName()) {
			$identifier = new IdentifierTypeNode($node->toString());
			$identifier->setAttribute('kind', IdentifierKind::ClassLike);
			$identifier->setAttribute('classLikeReference', new ClassLikeReferenceInfo($identifier->name));

		} else {
			$identifier = new IdentifierTypeNode($node->toString());
			$identifier->setAttribute('kind', IdentifierKind::Keyword);
		}

		return $identifier;
	}


	protected function processExprOrNull(?Node\Expr $expr): ?ExprInfo
	{
		return $expr ? $this->processExpr($expr) : null;
	}


	protected function processExpr(Node\Expr $expr): ExprInfo
	{
		if ($expr instanceof Node\Scalar\LNumber) {
			return new IntegerExprInfo($expr->value, $expr->getAttribute('kind'), $expr->getAttribute('rawValue'));

		} elseif ($expr instanceof Node\Scalar\DNumber) {
			return new FloatExprInfo($expr->value, $expr->getAttribute('rawValue'));

		} elseif ($expr instanceof Node\Scalar\String_) {
			return new StringExprInfo($expr->value, $expr->getAttribute('rawValue'));

		} elseif ($expr instanceof Node\Expr\Array_) {
			$items = [];

			foreach ($expr->items as $item) {
				assert($item !== null);
				$key = $this->processExprOrNull($item->key);
				$value = $this->processExpr($item->value);
				$items[] = new ArrayItemExprInfo($key, $value);
			}

			return new ArrayExprInfo($items);

		} elseif ($expr instanceof Node\Expr\ClassConstFetch) {
			assert($expr->class instanceof Node\Name);
			assert($expr->name instanceof Node\Identifier);

			// TODO: handle 'self' & 'parent' differently?
			return new ClassConstantFetchExprInfo($this->processName($expr->class), $expr->name->toString());

		} elseif ($expr instanceof Node\Expr\ConstFetch) {
			$lower = $expr->name->toLowerString();

			if ($lower === 'true') {
				return new BooleanExprInfo(true);

			} elseif ($lower === 'false') {
				return new BooleanExprInfo(false);

			} elseif ($lower === 'null') {
				return new NullExprInfo();

			} else {
				return new ConstantFetchExprInfo($expr->name->toString());
			}

		} elseif ($expr instanceof Node\Scalar\MagicConst) {
			return new ConstantFetchExprInfo($expr->getName());

		} elseif ($expr instanceof Node\Expr\UnaryMinus) {
			return new UnaryOpExprInfo('-', $this->processExpr($expr->expr));

		} elseif ($expr instanceof Node\Expr\UnaryPlus) {
			return new UnaryOpExprInfo('+', $this->processExpr($expr->expr));

		} elseif ($expr instanceof Node\Expr\BinaryOp) {
			return new BinaryOpExprInfo(
				$expr->getOperatorSigil(),
				$this->processExpr($expr->left),
				$this->processExpr($expr->right),
			);

		} elseif ($expr instanceof Node\Expr\Ternary) {
			return new TernaryExprInfo(
				$this->processExpr($expr->cond),
				$this->processExprOrNull($expr->if),
				$this->processExpr($expr->else),
			);

		} elseif ($expr instanceof Node\Expr\ArrayDimFetch) {
			assert($expr->dim !== null);
			return new DimFetchExprInfo(
				$this->processExpr($expr->var),
				$this->processExpr($expr->dim),
			);

		} elseif ($expr instanceof Node\Expr\PropertyFetch) {
			return new PropertyFetchExprInfo(
				$this->processExpr($expr->var),
				$expr->name instanceof Node\Expr ? $this->processExpr($expr->name) : $expr->name->name,
			);

		} elseif ($expr instanceof Node\Expr\NullsafePropertyFetch) {
			return new NullSafePropertyFetchExprInfo(
				$this->processExpr($expr->var),
				$expr->name instanceof Node\Expr ? $this->processExpr($expr->name) : $expr->name->name,
			);

		} elseif ($expr instanceof Node\Expr\New_) {
			assert($expr->class instanceof Name);

			$args = [];
			foreach ($expr->args as $arg) {
				assert($arg instanceof Node\Arg);
				$args[] = new ArgExprInfo($arg->name?->name, $this->processExpr($arg->value));
			}

			return new NewExprInfo($this->processName($expr->class), $args);

		} else {
			throw new \LogicException(sprintf('Unsupported expr node %s used in constant expression', get_debug_type($expr)));
		}
	}


	protected function extractPhpDoc(Node $node): PhpDocNode
	{
		return $node->getAttribute('phpDoc') ?? new PhpDocNode([]);
	}


	/**
	 * @return PhpDocTextNode[] indexed by []
	 */
	protected function extractMultiLineDescription(PhpDocNode $node): array
	{
		$textNodes = [];

		foreach ($node->children as $child) {
			if ($child instanceof PhpDocTextNode) {
				$textNodes[] = $child;

			} else {
				break;
			}
		}

		return $textNodes;
	}


	/**
	 * @return PhpDocTextNode[] indexed by []
	 */
	protected function extractSingleLineDescription(?PhpDocTagValueNode $tagValue): array
	{
		return $tagValue?->getAttribute('description') ?? [];
	}


	/**
	 * @return GenericParameterInfo[] indexed by [name]
	 */
	protected function extractGenericParameters(PhpDocNode $node): array
	{
		$genericParameters = [];

		foreach ($node->children as $child) {
			if ($child instanceof PhpDocTagNode && $child->value instanceof TemplateTagValueNode) {
				$lower = strtolower($child->value->name);

				$variance = match (true) {
					str_ends_with($child->name, '-covariant') => GenericParameterVariance::Covariant,
					str_ends_with($child->name, '-contravariant') => GenericParameterVariance::Contravariant,
					default => GenericParameterVariance::Invariant,
				};

				$genericParameters[$lower] = new GenericParameterInfo(
					name: $child->value->name,
					variance: $variance,
					bound: $child->value->bound,
					default: $child->value->default,
					description: $child->value->description,
				);
			}
		}

		return $genericParameters;
	}


	/**
	 * @return AliasInfo[] indexed by [name]
	 */
	protected function extractAliases(PhpDocNode $node, ?int $startLine, ?int $endLine): array
	{
		$aliases = [];

		foreach ($node->children as $child) {
			if ($child instanceof PhpDocTagNode && $child->value instanceof TypeAliasTagValueNode) {
				$lower = strtolower($child->value->alias);
				$aliases[$lower] = new AliasInfo($child->value->alias, $child->value->type);
				$aliases[$lower]->startLine = $startLine;
				$aliases[$lower]->endLine = $endLine;
			}
		}

		return $aliases;
	}


	/**
	 * @return PhpDocTagValueNode[][] indexed by [tagName][]
	 */
	protected function extractTags(PhpDocNode $node): array
	{
		$tags = [];

		foreach ($node->getTags() as $tag) {
			if (!$tag->value instanceof InvalidTagValueNode) {
				$tags[substr($tag->name, 1)][] = $tag->value;
			}
		}

		return $tags;
	}


	/**
	 * @return ParamTagValueNode[] indexed by [parameterName]
	 */
	protected function extractParamTagValues(PhpDocNode $node): array
	{
		$values = [];

		foreach ($node->children as $child) {
			if ($child instanceof PhpDocTagNode && $child->value instanceof ParamTagValueNode) {
				$values[$child->value->parameterName] = $child->value;
			}
		}

		return $values;
	}


	/**
	 * @return ClassLikeReferenceInfo[] indexed by [classLikeName]
	 */
	protected function extractDependencies(ClassLikeInfo | FunctionInfo $referencedBy): array
	{
		$dependencies = [];
		$stack = [$referencedBy];
		$index = 1;

		while ($index > 0) {
			$value = $stack[--$index];

			if ($value instanceof ClassLikeReferenceInfo && $value->fullLower !== 'self' && $value->fullLower !== 'parent') {
				$dependencies[$value->fullLower] ??= $value;
			}

			foreach ((array) $value as $item) {
				if (is_array($item) || is_object($item)) {
					$stack[$index++] = $item;
				}
			}
		}

		return $dependencies;
	}
}
