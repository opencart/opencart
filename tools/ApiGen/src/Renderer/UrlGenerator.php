<?php declare(strict_types = 1);

namespace ApiGen\Renderer;

use ApiGen\Index\NamespaceIndex;
use ApiGen\Info\AliasInfo;
use ApiGen\Info\ClassLikeInfo;
use ApiGen\Info\ConstantInfo;
use ApiGen\Info\EnumCaseInfo;
use ApiGen\Info\FunctionInfo;
use ApiGen\Info\MemberInfo;
use ApiGen\Info\MethodInfo;
use ApiGen\Info\ParameterInfo;
use ApiGen\Info\PropertyInfo;

use function assert;
use function get_debug_type;
use function sprintf;
use function str_starts_with;
use function strlen;
use function strrpos;
use function strtr;
use function substr;

use const DIRECTORY_SEPARATOR;


class UrlGenerator
{
	public function __construct(
		protected string $baseDir,
		protected string $baseUrl,
	) {
	}


	public function getRelativePath(string $path): string
	{
		if (str_starts_with($path, $this->baseDir)) {
			return substr($path, strlen($this->baseDir) + 1);

		} else {
			throw new \LogicException("{$path} does not start with {$this->baseDir}");
		}
	}


	public function getAssetUrl(string $name): string
	{
		return $this->baseUrl . $this->getAssetPath($name);
	}


	public function getAssetPath(string $name): string
	{
		return "assets/$name";
	}


	public function getIndexUrl(): string
	{
		return $this->baseUrl . $this->getIndexPath();
	}


	public function getIndexPath(): string
	{
		return 'index.html';
	}


	public function getTreeUrl(): string
	{
		return $this->baseUrl . $this->getTreePath();
	}


	public function getTreePath(): string
	{
		return 'tree.html';
	}


	public function getSitemapPath(): string
	{
		return 'sitemap.xml';
	}


	public function getSitemapUrl(): string
	{
		return $this->baseUrl . $this->getSitemapPath();
	}


	public function getNamespaceUrl(NamespaceIndex $namespace): string
	{
		return $this->baseUrl . $this->getNamespacePath($namespace);
	}


	public function getNamespacePath(NamespaceIndex $namespace): string
	{
		return 'namespace-' . strtr($namespace->name->full ?: 'none', '\\', '.') . '.html';
	}


	public function getClassLikeUrl(ClassLikeInfo $classLike): string
	{
		return $this->baseUrl . $this->getClassLikePath($classLike);
	}


	public function getClassLikePath(ClassLikeInfo $classLike): string
	{
		return strtr($classLike->name->full, '\\', '.') . '.html';
	}


	public function getClassLikeSourceUrl(ClassLikeInfo $classLike): string
	{
		assert($classLike->file !== null);
		return $this->getSourceUrl($classLike->file, $classLike->startLine, null); // intentionally not passing endLine
	}


	public function getMemberUrl(ClassLikeInfo $classLike, MemberInfo $member): string
	{
		return $this->getClassLikeUrl($classLike) . '#' . $this->getMemberAnchor($member);
	}


	public function getMemberAnchor(MemberInfo $member): string
	{
		if ($member instanceof ConstantInfo || $member instanceof EnumCaseInfo) {
			return $member->name;

		} elseif ($member instanceof PropertyInfo) {
			return '$' . $member->name;

		} elseif ($member instanceof MethodInfo) {
			return '_' . $member->name;

		} else {
			throw new \LogicException(sprintf('Unexpected member type %s', get_debug_type($member)));
		}
	}


	public function getMemberSourceUrl(ClassLikeInfo $classLike, MemberInfo $member): string
	{
		assert($classLike->file !== null);
		return $this->getSourceUrl($classLike->file, $member->startLine, $member->endLine);
	}


	public function getAliasUrl(ClassLikeInfo $classLike, AliasInfo $alias): string
	{
		return $this->getClassLikeUrl($classLike) . '#' . $this->getAliasAnchor($alias);
	}


	public function getAliasAnchor(AliasInfo $alias): string
	{
		return '~' . $alias->name;
	}


	public function getAliasSourceUrl(ClassLikeInfo $classLike, AliasInfo $alias): string
	{
		assert($classLike->file !== null);
		return $this->getSourceUrl($classLike->file, $alias->startLine, $alias->endLine);
	}


	public function getFunctionUrl(FunctionInfo $function): string
	{
		return $this->baseUrl . $this->getFunctionPath($function);
	}


	public function getFunctionPath(FunctionInfo $function): string
	{
		return 'function-' . strtr($function->name->full, '\\', '.') . '.html';
	}


	public function getFunctionSourceUrl(FunctionInfo $function): string
	{
		assert($function->file !== null);
		return $this->getSourceUrl($function->file, $function->startLine, $function->endLine);
	}


	public function getParameterAnchor(ParameterInfo $parameter): string
	{
		return '$' . $parameter->name;
	}


	public function getSourceUrl(string $path, ?int $startLine, ?int $endLine): string
	{
		if ($startLine === null) {
			$fragment = '';

		} elseif ($endLine === null || $endLine === $startLine) {
			$fragment = "#$startLine";

		} else {
			$fragment = "#$startLine-$endLine";
		}

		return $this->baseUrl . $this->getSourcePath($path) . $fragment;
	}


	public function getSourcePath(string $path): string
	{
		$relativePath = $this->getRelativePath($path);
		$relativePathWithoutExtension = substr($relativePath, 0, strrpos($relativePath, '.') ?: null);
		return 'source-' . strtr($relativePathWithoutExtension, DIRECTORY_SEPARATOR, '.') . '.html';
	}
}
