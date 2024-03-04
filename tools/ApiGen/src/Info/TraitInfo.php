<?php declare(strict_types = 1);

namespace ApiGen\Info;


class TraitInfo extends ClassLikeInfo
{
	/** @var ClassLikeReferenceInfo[] indexed by [classLikeName] */
	public array $uses = [];
}
