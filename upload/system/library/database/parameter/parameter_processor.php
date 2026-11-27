<?php

namespace Opencart\System\Library\Database\Parameter;

use Opencart\System\Library\Database\Parser\SqlParser;

/**
 * Coordinates SQL tokenization, binding resolution and expansion.
 */
final class ParameterProcessor {
	private SqlParser $parser;
	private ParameterExpander $expander;

	public function __construct(
		?SqlParser $parser = null,
		?ParameterExpander $expander = null
	) {
		$this->parser = $parser ?? new SqlParser();
		$this->expander = $expander ?? new ParameterExpander();
	}

	/**
	 * @param string                  $sql
	 * @param array<array-key, mixed> $params
	 * @param array<array-key, mixed> $types
	 */
	public function process(string $sql, array $params = [], array $types = []): ProcessedQuery {
		$tokens = $this->parser->tokenize($sql);

		$bindings = new ParameterBindings($tokens, $params, $types);

		$processedSql = '';
		$processedParams = [];
		$processedTypes = [];

		foreach ($tokens as $token) {
			if ($token['type'] === 'text') {
				$processedSql .= $token['value'];
				continue;
			}

			$binding = $bindings->resolve($token);
			$expanded = $this->expander->expand($binding);

			$processedSql .= $expanded['sql'];

			foreach ($expanded['params'] as $value) {
				$processedParams[] = $value;
			}

			foreach ($expanded['types'] as $type) {
				$processedTypes[] = $type;
			}
		}

		$bindings->validate();

		return new ProcessedQuery(
			$processedSql,
			$processedParams,
			$processedTypes
		);
	}
}
