<?php declare(strict_types = 1);

namespace ApiGen\Analyzer;

use ApiGen\Info\ClassLikeInfo;
use ApiGen\Info\ClassLikeReferenceInfo;
use ApiGen\Info\ErrorInfo;
use ApiGen\Info\FunctionInfo;
use ApiGen\Info\MissingInfo;
use ApiGen\Info\NameInfo;
use ApiGen\Scheduler;
use Symfony\Component\Console\Helper\ProgressBar;


class AnalyzeState
{
	/** @var true[] indexed by [path] */
	public array $files = [];

	/** @var MissingInfo[] indexed by [classLikeName] */
	public array $missing = [];

	/** @var ClassLikeInfo[] indexed by [classLikeName] */
	public array $classLikes = [];

	/** @var FunctionInfo[] indexed by [functionName] */
	public array $functions = [];

	/** @var ErrorInfo[][] indexed by [errorKind][] */
	public array $errors = [];

	/** @var NameInfo|null */
	public ?NameInfo $prevName = null;


	/**
	 * @param  Scheduler<AnalyzeTask, array<ClassLikeInfo | FunctionInfo | ClassLikeReferenceInfo | ErrorInfo>>   $scheduler
	 */
	public function __construct(
		public ProgressBar $progressBar,
		public Scheduler $scheduler,
	) {
	}
}
