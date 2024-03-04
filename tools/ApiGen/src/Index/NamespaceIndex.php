<?php declare(strict_types = 1);

namespace ApiGen\Index;

use ApiGen\Info\ClassInfo;
use ApiGen\Info\ElementInfo;
use ApiGen\Info\EnumInfo;
use ApiGen\Info\FunctionInfo;
use ApiGen\Info\InterfaceInfo;
use ApiGen\Info\NameInfo;
use ApiGen\Info\TraitInfo;


class NamespaceIndex implements ElementInfo
{
	/** @var ClassInfo[] indexed by [classShortName] (excludes exceptions) */
	public array $class = [];

	/** @var InterfaceInfo[] indexed by [interfaceShortName] */
	public array $interface = [];

	/** @var TraitInfo[] indexed by [traitShortName] */
	public array $trait = [];

	/** @var EnumInfo[] indexed by [enumShortName] */
	public array $enum = [];

	/** @var ClassInfo[] indexed by [exceptionShortName] */
	public array $exception = [];

	/** @var FunctionInfo[] indexed by [functionShortName] */
	public array $function = [];

	/** @var NamespaceIndex[] indexed by [namespaceShortName] */
	public array $children = [];


	public function __construct(
		public NameInfo $name,
		public bool $primary,
		public bool $deprecated,
	) {
	}


	public function isDeprecated(): bool
	{
		return $this->deprecated;
	}
}
