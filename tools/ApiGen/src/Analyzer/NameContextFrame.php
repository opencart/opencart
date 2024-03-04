<?php declare(strict_types = 1);

namespace ApiGen\Analyzer;

use ApiGen\Info\AliasReferenceInfo;
use ApiGen\Info\ClassLikeReferenceInfo;


class NameContextFrame
{
	/** @var null|NameContextFrame */
	public ?NameContextFrame $parent = null;

	/** @var null|ClassLikeReferenceInfo */
	public ?ClassLikeReferenceInfo $scope = null;

	/** @var true[] indexed by [name] */
	public array $genericParameters = [];

	/** @var AliasReferenceInfo[] indexed by [name]  */
	public array $aliases = [];


	public function __construct(?NameContextFrame $parent)
	{
		$this->parent = $parent;
		$this->scope = $parent?->scope;
		$this->genericParameters = $parent?->genericParameters ?? [];
		$this->aliases = $parent?->aliases ?? [];
	}
}
