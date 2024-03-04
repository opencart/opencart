<?php declare(strict_types = 1);

namespace ApiGen\Analyzer;

use ApiGen\Task\TaskHandlerFactory;


/**
 * @extends TaskHandlerFactory<null, AnalyzeTaskHandler>
 */
interface AnalyzeTaskHandlerFactory extends TaskHandlerFactory
{
	/**
	 * @param  null $context
	 */
	public function create(mixed $context): AnalyzeTaskHandler;
}
