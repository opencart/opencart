<?php

namespace Opencart\System\Library\Database\Parameter;

final class ProcessedQuery {
	/**
	 * @param list<mixed> $params
	 * @param list<ParameterType> $types
	 */
	public function __construct(
		public readonly string $sql,
		public readonly array $params,
		public readonly array $types
	) {
		if (!array_is_list($this->params) || !array_is_list($this->types)) {
			throw new \InvalidArgumentException(
				'Processed query parameters and types must be sequential arrays'
			);
		}

		if (count($this->params) !== count($this->types)) {
			throw new \InvalidArgumentException(
				'Processed query parameters and types count mismatch'
			);
		}

		foreach ($this->types as $type) {
			if (!$type instanceof ParameterType) {
				throw new \InvalidArgumentException(
					'Processed query contains an invalid parameter type'
				);
			}
		}
	}
}
