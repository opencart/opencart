<?php

namespace Opencart\System\Library\Database\Parameter;

/**
 * Converts one resolved binding into positional placeholders and values.
 */
final class ParameterExpander {
	/**
	 * @return array{sql: string, params: list<mixed>, types: list<ParameterType>}
	 */
	public function expand(ParameterBinding $binding): array {
		$value = $binding->value;
		$type = $binding->type;

		if (is_array($value)) {
			return $this->expandArray($value, $type);
		}

		if ($type instanceof ArrayParameterType) {
			throw new \InvalidArgumentException(
				'ArrayParameterType requires an array value'
			);
		}

		return $this->expandScalar($value, $type);
	}

	/**
	 * @return array{sql: string, params: list<mixed>, types: list<ParameterType>}
	 */
	private function expandArray(
		array $values,
		ParameterType|ArrayParameterType|null $type
	): array {
		if (!$type instanceof ArrayParameterType) {
			throw new \InvalidArgumentException(
				'Array SQL bindings require ArrayParameterType'
			);
		}

		if ($values === []) {
			throw new \InvalidArgumentException(
				'Array SQL binding cannot be empty; handle empty IN/NOT IN conditions before execution'
			);
		}

		$params = [];
		$types = [];

		foreach ($values as $value) {
			if (!is_scalar($value) && $value !== null) {
				throw new \InvalidArgumentException(
					'Unsupported array SQL binding value: ' . get_debug_type($value)
				);
			}

			$params[] = $value;
			$types[] = $value === null
				? ParameterType::NULL
				: $type->getParameterType();
		}

		return [
			'sql' => implode(', ', array_fill(0, count($params), '?')),
			'params' => $params,
			'types' => $types
		];
	}

	/**
	 * @return array{sql: string, params: list<mixed>, types: list<ParameterType>}
	 */
	private function expandScalar(
		mixed $value,
		?ParameterType $type
	): array {
		if (!is_scalar($value) && $value !== null) {
			throw new \InvalidArgumentException(
				'Unsupported SQL binding type: ' . get_debug_type($value)
			);
		}

		$resolvedType = $type ?? $this->inferType($value);

		if (
			($value === null && $resolvedType !== ParameterType::NULL) ||
			($value !== null && $resolvedType === ParameterType::NULL)
		) {
			throw new \InvalidArgumentException(
				'SQL binding value does not match its declared type'
			);
		}

		return [
			'sql' => '?',
			'params' => [$value],
			'types' => [$resolvedType]
		];
	}

	private function inferType(mixed $value): ParameterType {
		return match (true) {
			$value === null => ParameterType::NULL,
			is_bool($value) => ParameterType::BOOLEAN,
			is_int($value) => ParameterType::INTEGER,
			is_float($value) => ParameterType::FLOAT,
			is_string($value) => ParameterType::STRING,
			default => throw new \InvalidArgumentException(
				'Unsupported SQL binding type: ' . get_debug_type($value)
			)
		};
	}
}
