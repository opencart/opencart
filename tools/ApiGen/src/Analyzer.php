<?php declare(strict_types = 1);

namespace ApiGen;

use ApiGen\Analyzer\AnalyzeResult;
use ApiGen\Analyzer\AnalyzeState;
use ApiGen\Analyzer\AnalyzeTask;
use ApiGen\Analyzer\AnalyzeTaskHandlerFactory;
use ApiGen\Info\ClassLikeInfo;
use ApiGen\Info\ClassLikeReferenceInfo;
use ApiGen\Info\ErrorInfo;
use ApiGen\Info\ErrorKind;
use ApiGen\Info\FunctionInfo;
use ApiGen\Info\MissingInfo;
use ApiGen\Info\NameInfo;
use ApiGen\Scheduler\SchedulerFactory;
use Symfony\Component\Console\Helper\ProgressBar;

use function count;
use function implode;


class Analyzer
{
	public function __construct(
		protected SchedulerFactory $schedulerFactory,
		protected Locator $locator,
	) {
	}


	/**
	 * @param string[] $files indexed by []
	 */
	public function analyze(ProgressBar $progressBar, array $files): AnalyzeResult
	{
		$scheduler = $this->schedulerFactory->create(AnalyzeTaskHandlerFactory::class, context: null);
		$state = new AnalyzeState($progressBar, $scheduler);

		foreach ($files as $file) {
			$this->scheduleFile($state, $file, primary: true);
		}

		/** @var AnalyzeTask $task */
		foreach ($scheduler->process() as $task => $result) {
			$this->processTaskResult($result, $state);
			$progressBar->setMessage($task->sourceFile);
			$progressBar->advance();
		}

		foreach ($state->missing as $missing) {
			$referencedBy = $state->classLikes[$missing->referencedBy->fullLower] ?? $state->functions[$missing->referencedBy->fullLower];

			if ($referencedBy->primary) {
				$state->errors[ErrorKind::MissingSymbol->name][] = $this->createMissingSymbolError($missing, $referencedBy);
			}
		}

		return new AnalyzeResult($state->classLikes + $state->missing, $state->functions, $state->errors);
	}


	protected function scheduleFile(AnalyzeState $state, string $file, bool $primary): void
	{
		$file = Helpers::realPath($file);

		if (isset($state->files[$file])) {
			return;
		}

		$state->files[$file] = true;
		$state->progressBar->setMaxSteps(count($state->files));
		$state->scheduler->schedule(new AnalyzeTask($file, $primary));
	}


	/**
	 * @param  array<ClassLikeInfo | FunctionInfo | ClassLikeReferenceInfo | ErrorInfo> $result
	 */
	protected function processTaskResult(array $result, AnalyzeState $state): void
	{
		foreach ($result as $info) {
			match (true) {
				$info instanceof ClassLikeReferenceInfo => $this->processClassLikeReference($state, $info),
				$info instanceof ClassLikeInfo => $this->processClassLike($state, $info),
				$info instanceof FunctionInfo => $this->processFunction($state, $info),
				$info instanceof ErrorInfo => $this->processError($state, $info),
			};
		}
	}


	protected function processClassLikeReference(AnalyzeState $state, ClassLikeReferenceInfo $info): void
	{
		if ($state->prevName !== null && !isset($state->classLikes[$info->fullLower]) && !isset($state->missing[$info->fullLower])) {
			$name = new NameInfo($info->full, $info->fullLower);
			$state->missing[$info->fullLower] = new MissingInfo($name, $state->prevName);

			if (($file = $this->locator->locate($info)) !== null) {
				$this->scheduleFile($state, $file, primary: false);
			}
		}
	}


	protected function processClassLike(AnalyzeState $state, ClassLikeInfo $info): void
	{
		if (!isset($state->classLikes[$info->name->fullLower])) {
			unset($state->missing[$info->name->fullLower]);
			$state->classLikes[$info->name->fullLower] = $info;
			$state->prevName = $info->name;

		} else {
			$existing = $state->classLikes[$info->name->fullLower];
			$state->errors[ErrorKind::DuplicateSymbol->name][] = $this->createDuplicateSymbolError($info, $existing);
			$state->prevName = null;
		}
	}


	protected function processFunction(AnalyzeState $state, FunctionInfo $info): void
	{
		if (!isset($state->functions[$info->name->fullLower])) {
			$state->functions[$info->name->fullLower] = $info;
			$state->prevName = $info->name;

		} else {
			$existing = $state->functions[$info->name->fullLower];
			$state->errors[ErrorKind::DuplicateSymbol->name][] = $this->createDuplicateSymbolError($info, $existing);
			$state->prevName = null;
		}
	}


	protected function processError(AnalyzeState $state, ErrorInfo $info): void
	{
		$state->errors[$info->kind->name][] = $info;
		$state->prevName = null;
	}


	protected function createMissingSymbolError(MissingInfo $dependency, ClassLikeInfo | FunctionInfo $referencedBy): ErrorInfo
	{
		return new ErrorInfo(ErrorKind::MissingSymbol, implode("\n", [
			"Missing {$dependency->name->full}",
			"referenced by {$referencedBy->name->full}",
		]));
	}


	protected function createDuplicateSymbolError(ClassLikeInfo | FunctionInfo $info, ClassLikeInfo | FunctionInfo $first): ErrorInfo
	{
		return new ErrorInfo(ErrorKind::DuplicateSymbol, implode("\n", [
			"Multiple definitions of {$info->name->full}.",
			"The first definition was found in {$first->file} on line {$first->startLine}",
			"and then another one was found in {$info->file} on line {$info->startLine}",
		]));
	}
}
