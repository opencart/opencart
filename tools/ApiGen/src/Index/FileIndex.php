<?php declare(strict_types = 1);

namespace ApiGen\Index;

use ApiGen\Info\ClassLikeInfo;
use ApiGen\Info\FunctionInfo;


class FileIndex
{
	/** @var ClassLikeInfo[] indexed by [classLikeName] */
	public array $classLike = [];

	/** @var FunctionInfo[] indexed by [functionName] */
	public array $function = [];


	public function __construct(
		public string $path,
		public bool $primary,
	) {
	}
}
