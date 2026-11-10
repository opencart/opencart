<?php

namespace Opencart\System\Library\Database\Parameter;

/**
 * Resolves parsed placeholders against user-provided values and types.
 */
final class ParameterBindings {
	private const MODE_NONE = 'none';
	private const MODE_NAMED = 'named';
	private const MODE_POSITIONAL = 'positional';

	private string $mode;

	/**
	 * @var array<array-key, mixed>
	 */
	private array $params;

	/**
	 * @var array<array-key, ParameterType|ArrayParameterType>
	 */
	private array $types;

	/**
	 * @var array<array-key, true>
	 */
	private array $usedParams = [];

	/**
	 * @var array<array-key, true>
	 */
	private array $usedTypes = [];

	private int $position = 0;

	/**
	 * @param list<array{type: string, value: string}> $tokens
	 * @param array<array-key, mixed> $params
	 * @param array<array-key, ParameterType|ArrayParameterType> $types
	 */
	public function __construct(array $tokens, array $params, array $types) {
		$hasNamed = false;
		$hasPositional = false;

		foreach ($tokens as $token) {
			$hasNamed = $hasNamed || $token['type'] === 'named';
			$hasPositional = $hasPositional || $token['type'] === 'positional';
		}

		if ($hasNamed && $hasPositional) {
			throw new \InvalidArgumentException(
				'Mixing named and positional SQL parameters is not supported'
			);
		}

		if (!$hasNamed && !$hasPositional) {
			if ($params !== [] || $types !== []) {
				throw new \InvalidArgumentException(
					'SQL statement does not contain placeholders'
				);
			}

			$this->mode = self::MODE_NONE;
			$this->params = [];
			$this->types = [];

			return;
		}

		$this->mode = $hasNamed ? self::MODE_NAMED : self::MODE_POSITIONAL;

		if ($this->mode === self::MODE_NAMED) {
			$this->params = $this->normalizeNamed($params);
			$this->types = $this->normalizeNamed($types);
		} else {
			$this->params = $this->normalizePositional($params);
			$this->types = $this->validatePositionalTypes(
				$types,
				count($this->params)
			);
		}

		$this->validateTypes($this->types);
	}

	/**
	 * @param array{type: 'named'|'positional', value: string} $token
	 */
	public function resolve(array $token): ParameterBinding {
		if ($this->mode === self::MODE_NAMED) {
			if ($token['type'] !== 'named') {
				throw new \LogicException('Expected a named SQL parameter');
			}

			return $this->resolveNamed($token['value']);
		}

		if ($this->mode === self::MODE_POSITIONAL) {
			if ($token['type'] !== 'positional') {
				throw new \LogicException('Expected a positional SQL parameter');
			}

			return $this->resolvePositional();
		}

		throw new \LogicException('SQL statement does not contain parameters');
	}

	public function validate(): void {
		foreach ($this->params as $key => $value) {
			if (!array_key_exists($key, $this->usedParams)) {
				throw new \InvalidArgumentException('Unused SQL binding: ' . $key);
			}
		}

		foreach ($this->types as $key => $type) {
			if (!array_key_exists($key, $this->usedTypes)) {
				throw new \InvalidArgumentException('Unused SQL binding type: ' . $key);
			}
		}

		if (
			$this->mode === self::MODE_POSITIONAL &&
			$this->position !== count($this->params)
		) {
			throw new \InvalidArgumentException(
				'Unused positional SQL bindings detected'
			);
		}
	}

	private function resolveNamed(string $name): ParameterBinding {
		if (!array_key_exists($name, $this->params)) {
			throw new \InvalidArgumentException('Missing SQL binding: ' . $name);
		}

		$this->usedParams[$name] = true;

		$type = $this->types[$name] ?? null;

		if ($type !== null) {
			$this->usedTypes[$name] = true;
		}

		return new ParameterBinding($this->params[$name], $type);
	}

	private function resolvePositional(): ParameterBinding {
		$position = $this->position++;

		if (!array_key_exists($position, $this->params)) {
			throw new \InvalidArgumentException(
				'Missing positional SQL binding at index ' . $position
			);
		}

		$this->usedParams[$position] = true;
		$type = $this->types[$position] ?? null;

		if ($type !== null) {
			$this->usedTypes[$position] = true;
		}

		return new ParameterBinding($this->params[$position], $type);
	}

	/**
	 * @param array<array-key, mixed> $items
	 * @return array<array-key, mixed>
	 */
	private function normalizeNamed(array $items): array {
		$result = [];

		foreach ($items as $key => $value) {
			$name = (string)$key;

			if (str_starts_with($name, ':')) {
				$name = substr($name, 1);
			}

			if (!preg_match('/^(?:[a-zA-Z_][a-zA-Z0-9_]*|[0-9]+)$/D', $name)) {
				throw new \InvalidArgumentException(
					'Invalid SQL binding name: ' . (string)$key
				);
			}

			if (array_key_exists($name, $result)) {
				throw new \InvalidArgumentException('Duplicate SQL binding: ' . $name);
			}

			$result[$name] = $value;
		}

		return $result;
	}

	/**
	 * @param list<mixed> $params
	 * @return list<mixed>
	 */
	private function normalizePositional(array $params): array {
		if (!array_is_list($params)) {
			throw new \InvalidArgumentException(
				'Positional bindings must be a sequential array'
			);
		}

		return $params;
	}

	/**
	 * @param array<array-key, ParameterType|ArrayParameterType> $types
	 * @return array<int, ParameterType|ArrayParameterType>
	 */
	private function validatePositionalTypes(array $types, int $count): array {
		foreach ($types as $key => $type) {
			if (!is_int($key) || $key < 0 || $key >= $count) {
				throw new \InvalidArgumentException(
					'Invalid positional binding type index: ' . (string)$key
				);
			}
		}

		return $types;
	}

	/**
	 * @param array<array-key, ParameterType|ArrayParameterType> $types
	 */
	private function validateTypes(array $types): void {
		foreach ($types as $key => $type) {
			if (
				!$type instanceof ParameterType &&
				!$type instanceof ArrayParameterType
			) {
				throw new \InvalidArgumentException(
					'Unsupported SQL parameter type for binding: ' . (string)$key
				);
			}
		}
	}
}
