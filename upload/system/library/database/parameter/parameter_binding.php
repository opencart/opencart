<?php

namespace Opencart\System\Library\Database\Parameter;

final class ParameterBinding {
	public function __construct(
		public readonly mixed $value,
		public readonly null|ArrayParameterType|ParameterType $type = null
	) {}
}
