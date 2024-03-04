<?php declare(strict_types = 1);

namespace ApiGen;

use ApiGen\Analyzer\AnalyzeResult;
use ApiGen\Index\Index;
use ApiGen\Info\ErrorKind;
use Nette\Utils\Finder;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Style\OutputStyle;

use function array_column;
use function array_slice;
use function count;
use function hrtime;
use function implode;
use function is_dir;
use function is_file;
use function memory_get_peak_usage;
use function memory_reset_peak_usage;
use function sprintf;

use const PHP_VERSION_ID;


class ApiGen
{
	/**
	 * @param string[] $paths   indexed by []
	 * @param string[] $include indexed by []
	 * @param string[] $exclude indexed by []
	 */
	public function __construct(
		protected OutputStyle $output,
		protected Analyzer $analyzer,
		protected Indexer $indexer,
		protected Renderer $renderer,
		protected array $paths,
		protected array $include,
		protected array $exclude,
	) {
	}


	public function generate(): bool
	{
		$files = $this->findFiles();

		PHP_VERSION_ID >= 80200 && memory_reset_peak_usage();
		$analyzeTime = -hrtime(true);
		$analyzeResult = $this->analyze($files);
		$analyzeTime += hrtime(true);
		$analyzeMemory = memory_get_peak_usage();

		PHP_VERSION_ID >= 80200 && memory_reset_peak_usage();
		$indexTime = -hrtime(true);
		$index = $this->index($analyzeResult);
		$indexTime += hrtime(true);
		$indexMemory = memory_get_peak_usage();

		PHP_VERSION_ID >= 80200 && memory_reset_peak_usage();
		$renderTime = -hrtime(true);
		$this->render($index);
		$renderTime += hrtime(true);
		$renderMemory = memory_get_peak_usage();

		$this->performance($analyzeTime, $analyzeMemory, $indexTime, $indexMemory, $renderTime, $renderMemory);
		return $this->finish($analyzeResult);
	}


	/**
	 * @return string[] list of files, indexed by []
	 */
	protected function findFiles(): array
	{
		$files = [];
		$dirs = [];

		foreach ($this->paths as $path) {
			if (is_file($path)) {
				$files[] = $path;

			} elseif (is_dir($path)) {
				$dirs[] = $path;

			} else {
				$this->output->error(sprintf('Path "%s" does not exist.', $path));
			}
		}

		if (count($dirs) > 0) {
			$finder = Finder::findFiles($this->include)
				->exclude($this->exclude)
				->from($dirs);

			foreach ($finder as $file => $_) {
				$files[] = $file;
			}
		}

		if (count($files) === 0) {
			throw new \RuntimeException('No source files found.');

		} elseif ($this->output->isDebug()) {
			$this->output->text('<info>Matching source files:</info>');
			$this->output->newLine();
			$this->output->listing($files);

		} elseif ($this->output->isVerbose()) {
			$this->output->text(sprintf('Found %d source files.', count($files)));
			$this->output->newLine();
		}

		return $files;
	}


	/**
	 * @param string[] $files indexed by []
	 */
	protected function analyze(array $files): AnalyzeResult
	{
		$progressBar = $this->createProgressBar('Analyzing');
		$result = $this->analyzer->analyze($progressBar, $files);

		if ($progressBar->getMaxSteps() === $progressBar->getProgress()) {
			$progressBar->setMessage('done');
			$progressBar->finish();
		}

		$this->output->newLine(2);

		return $result;
	}


	protected function index(AnalyzeResult $analyzeResult): Index
	{
		$index = new Index();

		foreach ($analyzeResult->classLike as $info) {
			$this->indexer->indexFile($index, $info->file, $info->primary);
			$this->indexer->indexNamespace($index, $info->name->namespace, $info->name->namespaceLower, $info->primary, $info->isDeprecated());
			$this->indexer->indexClassLike($index, $info);
		}

		foreach ($analyzeResult->function as $info) {
			$this->indexer->indexFile($index, $info->file, $info->primary);
			$this->indexer->indexNamespace($index, $info->name->namespace, $info->name->namespaceLower, $info->primary, $info->isDeprecated());
			$this->indexer->indexFunction($index, $info);
		}

		$this->indexer->postProcess($index);
		return $index;
	}


	protected function render(Index $index): void
	{
		$progressBar = $this->createProgressBar('Rendering');
		$this->renderer->render($progressBar, $index);

		if ($progressBar->getMaxSteps() === $progressBar->getProgress()) {
			$progressBar->setMessage('done');
			$progressBar->finish();
		}

		$this->output->newLine(2);
	}


	protected function createProgressBar(string $label): ProgressBar
	{
		$progressBar = $this->output->createProgressBar();
		$progressBar->setFormat(" <fg=green>$label</> %current%/%max% %bar% %percent:3s%% %message%");
		$progressBar->setBarCharacter("\u{2588}");
		$progressBar->setProgressCharacter('_');
		$progressBar->setEmptyBarCharacter('_');

		return $progressBar;
	}


	protected function performance(float $analyzeTime, int $analyzeMemory, float $indexTime, int $indexMemory, float $renderTime, int $renderMemory): void
	{
		if ($this->output->isVeryVerbose()) {
			$lines = [
				'Analyze time' => sprintf('%6.0f ms', $analyzeTime / 1e6),
				'Index time' => sprintf('%6.0f ms', $indexTime / 1e6),
				'Render time' => sprintf('%6.0f ms', $renderTime / 1e6),
				'' => '',
				'Analyze peak memory' => sprintf('%6.0f MB', $analyzeMemory / 1e6),
				'Index peak memory' => sprintf('%6.0f MB', $indexMemory / 1e6),
				'Render peak memory' => sprintf('%6.0f MB', $renderMemory / 1e6),
			];

			foreach ($lines as $label => $value) {
				$this->output->text(sprintf('<info>%-20s</info> %s', $label, $value));
			}
		}
	}


	protected function finish(AnalyzeResult $analyzeResult): bool
	{
		if (count($analyzeResult->error) === 0) {
			$this->output->success('Finished OK');
			return true;
		}

		$hasError = false;
		foreach ($analyzeResult->error as $errorKind => $errorGroup) {
			$errorLines = array_column($errorGroup, 'message');

			if (!$this->output->isVerbose() && count($errorLines) > 5) {
				$errorLines = array_slice($errorLines, 0, 5);
				$errorLines[] = '...';
				$errorLines[] = sprintf('and %d more (use --verbose to show all)', count($errorGroup) - 5);
			}

			if ($errorKind === ErrorKind::InternalError->name) {
				$hasError = true;
				$this->output->error(implode("\n\n", $errorLines));

			} else {
				$this->output->warning(implode("\n\n", $errorLines));
			}
		}

		if ($hasError) {
			$this->output->error('Finished with errors');
			return false;
		}

		$this->output->success('Finished with warnings');
		return true;
	}
}
