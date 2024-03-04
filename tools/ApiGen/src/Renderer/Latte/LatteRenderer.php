<?php declare(strict_types = 1);

namespace ApiGen\Renderer\Latte;

use ApiGen\Index\Index;
use ApiGen\Renderer;
use ApiGen\Renderer\Filter;
use ApiGen\Renderer\Latte\Template\ConfigParameters;
use ApiGen\Scheduler;
use ApiGen\Scheduler\SchedulerFactory;
use Nette\Utils\FileSystem;
use Nette\Utils\Finder;
use Symfony\Component\Console\Helper\ProgressBar;


class LatteRenderer implements Renderer
{
	public function __construct(
		protected SchedulerFactory $schedulerFactory,
		protected Filter $filter,
		protected string $title,
		protected string $version,
		protected string $outputDir,
	) {
	}


	public function render(ProgressBar $progressBar, Index $index): void
	{
		$this->prepareOutputDir();
		$scheduler = $this->createScheduler($index);

		foreach ($this->getRenderTasks($index) as $task) {
			$scheduler->schedule($task);
			$progressBar->setMaxSteps($progressBar->getMaxSteps() + 1);
		}

		foreach ($scheduler->process() as $path) {
			$progressBar->setMessage($path);
			$progressBar->advance();
		}
	}


	protected function prepareOutputDir(): void
	{
		FileSystem::delete($this->outputDir);
		FileSystem::createDir($this->outputDir);
	}


	/**
	 * @return Scheduler<LatteRenderTask, string>
	 */
	protected function createScheduler(Index $index): Scheduler
	{
		$context = new LatteRenderTaskContext($index, new ConfigParameters($this->title, $this->version));
		return $this->schedulerFactory->create(LatteRenderTaskHandlerFactory::class, $context);
	}


	/**
	 * @return iterable<LatteRenderTask>
	 */
	protected function getRenderTasks(Index $index): iterable
	{
		yield from $this->getAssetsCopyTasks();
		yield from $this->getUnitRenderTasks();
		yield from $this->getNamespaceRenderTasks($index);
		yield from $this->getClassLikeRenderTasks($index);
		yield from $this->getFunctionRenderTasks($index);
		yield from $this->getSourceRenderTasks($index);
	}


	/**
	 * @return iterable<LatteRenderTask>
	 */
	protected function getAssetsCopyTasks(): iterable
	{
		foreach (Finder::findFiles(__DIR__ . '/Template/assets/*') as $asset) {
			yield new LatteRenderTask(LatteRenderTaskType::Asset, $asset->getPathname());
		}
	}


	/**
	 * @return iterable<LatteRenderTask>
	 */
	protected function getUnitRenderTasks(): iterable
	{
		yield new LatteRenderTask(LatteRenderTaskType::ElementsJs);
		yield new LatteRenderTask(LatteRenderTaskType::Index);

		if ($this->filter->filterTreePage()) {
			yield new LatteRenderTask(LatteRenderTaskType::Tree);
		}

		if ($this->filter->filterSitemapPage()) {
			yield new LatteRenderTask(LatteRenderTaskType::Sitemap);
		}
	}


	/**
	 * @return iterable<LatteRenderTask>
	 */
	protected function getNamespaceRenderTasks(Index $index): iterable
	{
		foreach ($index->namespace as $namespaceKey => $namespace) {
			if ($this->filter->filterNamespacePage($namespace)) {
				yield new LatteRenderTask(LatteRenderTaskType::Namespace, $namespaceKey);
			}
		}
	}


	/**
	 * @return iterable<LatteRenderTask>
	 */
	protected function getClassLikeRenderTasks(Index $index): iterable
	{
		foreach ($index->classLike as $classLikeKey => $classLike) {
			if ($this->filter->filterClassLikePage($classLike)) {
				yield new LatteRenderTask(LatteRenderTaskType::ClassLike, $classLikeKey);
			}
		}
	}


	/**
	 * @return iterable<LatteRenderTask>
	 */
	protected function getFunctionRenderTasks(Index $index): iterable
	{
		foreach ($index->function as $functionKey => $function) {
			if ($this->filter->filterFunctionPage($function)) {
				yield new LatteRenderTask(LatteRenderTaskType::Function, $functionKey);
			}
		}
	}


	/**
	 * @return iterable<LatteRenderTask>
	 */
	protected function getSourceRenderTasks(Index $index): iterable
	{
		foreach ($index->files as $fileKey => $file) {
			if ($this->filter->filterSourcePage($file)) {
				yield new LatteRenderTask(LatteRenderTaskType::Source, $fileKey);
			}
		}
	}
}
