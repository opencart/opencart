<?php

namespace Opencart\System\Library\Database\Parameter;

enum ParameterType: string {
	case NULL = 'null';
	case BOOLEAN = 'boolean';
	case INTEGER = 'integer';
	case FLOAT = 'float';
	case STRING = 'string';
}
