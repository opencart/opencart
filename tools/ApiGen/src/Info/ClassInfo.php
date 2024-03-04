<?php declare(strict_types = 1);

namespace ApiGen\Info;


class ClassInfo extends ClassLikeInfo
{
	/** @var bool */
	public bool $abstract = false;

	/** @var bool */
	public bool $final = false;

	/** @var bool */
	public bool $readOnly = false;

	/** @var ClassLikeReferenceInfo|null */
	public ?ClassLikeReferenceInfo $extends = null;

	/** @var ClassLikeReferenceInfo[] indexed by [classLikeName] */
	public array $implements = [];

	/** @var ClassLikeReferenceInfo[] indexed by [classLikeName] */
	public array $uses = [];
}
