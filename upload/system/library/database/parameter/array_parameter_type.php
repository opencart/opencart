<?php

namespace Opencart\System\Library\Database\Parameter;

enum ArrayParameterType: string {
	case BOOLEAN = 'boolean';
	case INTEGER = 'integer';
	case FLOAT = 'float';
	case STRING = 'string';

	public function getParameterType(): ParameterType {
		return match ($this) {
			self::BOOLEAN => ParameterType::BOOLEAN,
			self::INTEGER => ParameterType::INTEGER,
			self::FLOAT => ParameterType::FLOAT,
			self::STRING => ParameterType::STRING
		};
	}
}
