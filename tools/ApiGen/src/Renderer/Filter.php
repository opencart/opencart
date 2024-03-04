<?php declare(strict_types = 1);

namespace ApiGen\Renderer;

use ApiGen\Index\FileIndex;
use ApiGen\Index\NamespaceIndex;
use ApiGen\Info\ClassLikeInfo;
use ApiGen\Info\FunctionInfo;
use ApiGen\Info\MissingInfo;


class Filter
{
	public function filterTreePage(): bool
	{
		return true;
	}


	public function filterSitemapPage(): bool
	{
		return true;
	}


	public function filterNamespacePage(NamespaceIndex $namespace): bool
	{
		return true;
	}


	public function filterClassLikePage(ClassLikeInfo $classLike): bool
	{
		return !$classLike instanceof MissingInfo;
	}


	public function filterFunctionPage(FunctionInfo $function): bool
	{
		return true;
	}


	public function filterSourcePage(FileIndex $file): bool
	{
		return $file->primary;
	}
}
