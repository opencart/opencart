<?php declare(strict_types = 1);

namespace ApiGen\Info\Traits;

use ApiGen\Info\GenericParameterInfo;


trait HasGenericParameters
{
	/** @var GenericParameterInfo[] indexed by [parameterName] */
	public array $genericParameters = [];
}
