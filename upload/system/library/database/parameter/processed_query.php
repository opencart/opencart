<?php

namespace Opencart\System\Library\Database\Parameter;

final class ProcessedQuery {
	public readonly string $sql;

	/** @var list<mixed> */
	public readonly array $params;

	/** @var list<ParameterType> */
	public readonly array $types;

	/**
	 * @param array<array-key, mixed> $params
	 * @param array<array-key, mixed> $types
	 */
	public function __construct(string $sql, array $params, array $types) {
		if (!array_is_list($params) || !array_is_list($types)) {
			throw new \InvalidArgumentException(
				'Processed query parameters and types must be sequential arrays'
			);
		}

		if (count($params) !== count($types)) {
			throw new \InvalidArgumentException(
				'Processed query parameters and types count mismatch'
			);
		}

		foreach ($types as $type) {
			if (!$type instanceof ParameterType) {
				throw new \InvalidArgumentException(
					'Processed query contains an invalid parameter type'
				);
			}
		}

		$this->sql = $sql;

		/** @var list<mixed> $params */
		$this->params = $params;

		/** @var list<ParameterType> $types */
		$this->types = $types;
	}
}
