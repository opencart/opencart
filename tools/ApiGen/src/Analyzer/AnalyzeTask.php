<?php declare(strict_types = 1);

namespace ApiGen\Analyzer;

use ApiGen\Task\Task;


class AnalyzeTask implements Task
{
	public function __construct(
		public string $sourceFile,
		public bool $primary,
	) {
	}
}
