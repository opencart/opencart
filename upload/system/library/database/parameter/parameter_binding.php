<?php

namespace Opencart\System\Library\Database\Parameter;

final class ParameterBinding {
	public function __construct(
		public readonly mixed $value,
		public readonly ParameterType|ArrayParameterType|null $type = null
	) {
	}
}
