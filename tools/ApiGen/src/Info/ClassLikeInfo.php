<?php declare(strict_types = 1);

namespace ApiGen\Info;

use ApiGen\Index\Index;
use ApiGen\Info\Traits\HasGenericParameters;
use ApiGen\Info\Traits\HasLineLocation;
use ApiGen\Info\Traits\HasTags;


abstract class ClassLikeInfo implements ElementInfo
{
	use HasTags;
	use HasLineLocation;
	use HasGenericParameters;


	/** @var string|null */
	public ?string $file = null;

	/** @var ConstantInfo[] indexed by [constantName] */
	public array $constants = [];

	/** @var PropertyInfo[] indexed by [propertyName] */
	public array $properties = [];

	/** @var MethodInfo[] indexed by [methodName] */
	public array $methods = [];

	/** @var ClassLikeReferenceInfo[] indexed by [classLikeName] */
	public array $mixins = [];

	/** @var AliasInfo[] indexed by [aliasName] */
	public array $aliases = [];


	public function __construct(
		public NameInfo $name,
		public bool $primary,
	) {
	}


	public function isInstanceOf(Index $index, string $type): bool
	{
		return isset($index->instanceOf[$type][$this->name->fullLower]);
	}


	public function isThrowable(Index $index): bool
	{
		return $this->isInstanceOf($index, 'throwable');
	}
}
