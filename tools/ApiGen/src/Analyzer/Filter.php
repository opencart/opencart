<?php declare(strict_types = 1);

namespace ApiGen\Analyzer;

use ApiGen\Info\ClassLikeInfo;
use ApiGen\Info\FunctionInfo;
use ApiGen\Info\MemberInfo;
use PhpParser\Node;
use PHPStan\PhpDocParser\Ast\PhpDoc\PhpDocTagValueNode;


class Filter
{
	/** @var int */
	protected int $excludedVisibilityMask = 0;


	/**
	 * @param string[] $excludeTagged indexed by []
	 */
	public function __construct(
		bool $excludeProtected,
		bool $excludePrivate,
		protected array $excludeTagged,
	) {
		$this->excludedVisibilityMask |= ($excludeProtected ? Node\Stmt\Class_::MODIFIER_PROTECTED : 0);
		$this->excludedVisibilityMask |= ($excludePrivate ? Node\Stmt\Class_::MODIFIER_PRIVATE : 0);
	}


	public function filterClassLikeNode(Node\Stmt\ClassLike $node): bool
	{
		return true;
	}


	/**
	 * @param PhpDocTagValueNode[][] $tags indexed by [tagName][]
	 */
	public function filterClassLikeTags(array $tags): bool
	{
		foreach ($this->excludeTagged as $tag) {
			if (isset($tags[$tag])) {
				return false;
			}
		}

		return true;
	}


	public function filterClassLikeInfo(ClassLikeInfo $info): bool
	{
		return true;
	}


	public function filterFunctionNode(Node\Stmt\Function_ $node): bool
	{
		return true;
	}


	/**
	 * @param PhpDocTagValueNode[][] $tags indexed by [tagName][]
	 */
	public function filterFunctionTags(array $tags): bool
	{
		foreach ($this->excludeTagged as $tag) {
			if (isset($tags[$tag])) {
				return false;
			}
		}

		return true;
	}


	public function filterFunctionInfo(FunctionInfo $info): bool
	{
		return true;
	}


	public function filterConstantNode(Node\Stmt\ClassConst $node): bool
	{
		return ($node->flags & $this->excludedVisibilityMask) === 0;
	}


	public function filterPropertyNode(Node\Stmt\Property $node): bool
	{
		return ($node->flags & $this->excludedVisibilityMask) === 0;
	}


	public function filterPromotedPropertyNode(Node\Param $node): bool
	{
		return ($node->flags & $this->excludedVisibilityMask) === 0;
	}


	public function filterMethodNode(Node\Stmt\ClassMethod $node): bool
	{
		return ($node->flags & $this->excludedVisibilityMask) === 0;
	}


	public function filterEnumCaseNode(Node\Stmt\EnumCase $node): bool
	{
		return true;
	}


	/**
	 * @param PhpDocTagValueNode[][] $tags indexed by [tagName][]
	 */
	public function filterMemberTags(array $tags): bool
	{
		foreach ($this->excludeTagged as $tag) {
			if (isset($tags[$tag])) {
				return false;
			}
		}

		return true;
	}


	public function filterMemberInfo(ClassLikeInfo $classLike, MemberInfo $member): bool
	{
		return true;
	}
}
